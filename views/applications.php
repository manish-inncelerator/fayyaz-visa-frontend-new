<?php
defined('BASE_DIR') || die('Direct access denied');

// Start session only if not already active
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Check if user is logged in
if (empty($_SESSION['user_id'])) {
    header('Location: auth/login');
    exit();
}


// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include required files
require 'inc/html_head.php';
require 'inc/html_foot.php';
require 'database.php';
require 'min.php';
require 'imgClass.php';

// Output HTML head and scripts
echo html_head('My Applications', null, true, false, true);

function timeAgo($datetime)
{
    // Ensure $datetime is not null or empty
    if (empty($datetime) || $datetime === '0000-00-00 00:00:00') {
        return 'No timestamp available';
    }

    $timestamp = strtotime($datetime);

    // If conversion fails, return a fallback message
    if ($timestamp === false) {
        return 'Invalid date';
    }

    $diff = time() - $timestamp;

    if ($diff < 0) {
        return 'Just now';
    }

    $units = [
        'year'   => 31536000,
        'month'  => 2592000,
        'week'   => 604800,
        'day'    => 86400,
        'hour'   => 3600,
        'minute' => 60,
        'second' => 1
    ];

    foreach ($units as $unit => $value) {
        if ($diff >= $value) {
            $count = round($diff / $value);
            return "$count $unit" . ($count > 1 ? 's' : '') . ' ago';
        }
    }

    return 'Just now';
}

// Pagination settings
$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Count total orders (unfinished and completed)
$order_count = $database->count('orders', [
    'order_user_id' => $_SESSION['user_id'],
    'is_finished' => 0,
    'is_archive' => 0
]);

$completed_count = $database->count('orders', [
    'order_user_id' => $_SESSION['user_id'],
    'is_finished' => 1
]);

// Fetch paginated orders sorted by newest first
$orders = $database->select('orders', '*', [
    'order_user_id' => $_SESSION['user_id'],
    "ORDER" => ["id" => "DESC"], // Order by latest
    "LIMIT" => [$offset, $limit]
]);

// Fetch completed orders
$completed_orders = $database->select('orders', '*', [
    'order_user_id' => $_SESSION['user_id'],
    'is_paid' => 1,
    "ORDER" => ["id" => "DESC"], // Order by latest
    "LIMIT" => [$offset, $limit]
]);

// Calculate total pages for pagination
$total_pages = ceil($order_count / $limit);

// Fetch archived orders
$archived_orders = $database->select('orders', '*', [
    'order_user_id' => $_SESSION['user_id'],
    'is_archive' => 1,
    "ORDER" => ["id" => "DESC"], // Order by latest
    "LIMIT" => [$offset, $limit]
]);

// Fetch draft (unfinished & not archived) orders
$draft_orders = $database->select('orders', '*', [
    'order_user_id' => $_SESSION['user_id'],
    'is_finished' => 0,
    'is_archive' => 0,
    "ORDER" => ["id" => "DESC"], // Order by latest
    "LIMIT" => [$offset, $limit]
]);

// Calculate total pages for different order categories
$total_draft_pages = ceil($database->count('orders', [
    'order_user_id' => $_SESSION['user_id'],
    'is_finished' => 0,
    'is_archive' => 0
]) / $limit);

$total_completed_pages = ceil($database->count('orders', [
    'order_user_id' => $_SESSION['user_id'],
    'is_finished' => 1
]) / $limit);

$total_archived_pages = ceil($database->count('orders', [
    'order_user_id' => $_SESSION['user_id'],
    'is_archive' => 1
]) / $limit);

?>

<?php
function getLastUpdateTimestamp($database, $order_id)
{
    $tables = [
        'applicants',
        'travel_documents',
        'addresses',
        'occupation_education',
        'additional_information',
        'visit_information',
        'antecedent_information',
        'declaration_terms'
    ];

    $latestTimestamp = '0000-00-00 00:00:00'; // Initialize with default value

    foreach ($tables as $table) {
        $result = $database->get($table, [
            "last_updated" => Medoo\Medoo::raw("COALESCE(created_at, '0000-00-00 00:00:00')")
        ], [
            "order_id" => $order_id
        ]);

        if (!empty($result) && !empty($result['last_updated']) && $result['last_updated'] !== '0000-00-00 00:00:00') {
            if ($latestTimestamp === '0000-00-00 00:00:00' || strtotime($result['last_updated']) > strtotime($latestTimestamp)) {
                $latestTimestamp = $result['last_updated'];
            }
        }
    }

    return ($latestTimestamp !== '0000-00-00 00:00:00') ? $latestTimestamp : null;
}


function getCountryName($database, $country_id)
{
    // Fetch country name from the 'countries' table
    $result = $database->get("visa_countries", "country_name", [
        "country_id" => $country_id
    ]);

    // Return country name if found, otherwise return null
    return $result ?? null;
}
?>

<body class="d-flex flex-column min-vh-100">
    <!-- Navbar -->
    <?php
    if (isset($_SESSION['user_id'])) {
        require 'components/LoggedinNavbar.php';
    } else {
        require 'components/Navbar.php';
    }
    ?>
    <!-- ./Navbar -->

    <!-- Applications Page -->
    <main class="container my-2 flex-grow-1 overflow-auto">
        <div class="row">
            <div class="col-12 mb-2">
                <h1><b>My Applications</b></h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/" class="text-decoration-none text-golden">
                                <i class="bi bi-house"></i> Home
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">My Applications</li>
                    </ol>
                </nav>
            </div>

            <div class="col-12">
                <!-- Tabs Navigation -->
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active link-golden text-decoration-none" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
                            <i class="bi bi-file-earmark"></i> All Applications
                        </button>
                        <button class="nav-link link-golden text-decoration-none" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">
                            <i class="bi bi-archive"></i> Archive
                        </button>
                    </div>
                </nav>

                <!-- Tab Content -->
                <div class="tab-content" id="nav-tabContent">
                    <!-- All Applications -->
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <!-- Draft Applications -->
                        <div class="card mt-3">
                            <div class="card-header bg-light fw-bold text-muted">
                                <i class="bi bi-pencil-square"></i> Draft Applications (<?= $order_count; ?>)
                            </div>
                            <div class="card-body table-responsive">
                                <?php if (!empty($draft_orders)): ?>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Destination</th>
                                                <th>Ordered</th>
                                                <th>Last Edited</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($draft_orders as $order): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($order['order_id']); ?></td>
                                                    <td><?= htmlspecialchars(getCountryName($database, $order['country_id']) ?? 'Unknown'); ?></td>
                                                    <td><?= timeAgo($order['order_date']); ?></td>
                                                    <td><?= timeAgo(getLastUpdateTimestamp($database, $order['order_id'])); ?></td>
                                                    <td>
                                                        <a href="application/<?= $order['order_id']; ?>/persona" class="btn-sm btn rounded-pill btn-blue">Resume Application</a>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-link" onClick="sendToArchive('<?= $order['order_id']; ?>', this)">
                                                            <i class="bi bi-archive fs-5 link-golden"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <!-- Pagination -->
                                    <nav>
                                        <ul class="pagination justify-content-center">
                                            <?php for ($i = 1; $i <= $total_draft_pages; $i++): ?>
                                                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                                    <a class="page-link" href="applications?page=<?= $i; ?>#nav-home">
                                                        <?= $i; ?>
                                                    </a>
                                                </li>
                                            <?php endfor; ?>
                                        </ul>
                                    </nav>
                                <?php else: ?>
                                    <p class="card-text">No draft applications available.</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Completed Applications -->
                        <div class="card mt-3">
                            <div class="card-header bg-light fw-bold text-muted">
                                <i class="bi bi-file-earmark-check"></i> Completed Applications (<?= $completed_count; ?>)
                            </div>
                            <div class="card-body table-responsive">
                                <?php if (!empty($completed_orders)): ?>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Destination</th>
                                                <th>Order Date</th>
                                                <th>Status</th>
                                                <!-- <th>Action</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($completed_orders as $completed): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($completed['order_id']); ?></td>
                                                    <td><?= htmlspecialchars(getCountryName($database, $completed['country_id'])); ?></td>
                                                    <td><?= timeAgo($completed['order_date']); ?></td>
                                                    <td>
                                                        <?php
                                                        if ($completed['is_finished'] && $completed['is_paid']) {
                                                            echo '<span class="badge bg-success">Ordered</span>';
                                                        } elseif ($completed['is_processing'] && $completed['is_paid']) {
                                                            echo '<span class="badge bg-warning">In Processing</span>';
                                                        } elseif ($completed['is_screening'] && $completed['is_paid']) {
                                                            echo '<span class="badge bg-warning">In Screening</span>';
                                                        } elseif ($completed['is_rejected'] && $completed['is_paid']) {
                                                            echo '<span class="badge bg-danger">Application Rejected</span>';
                                                        } elseif ($completed['is_cancelled_by_us'] && $completed['is_paid']) {
                                                            echo '<span class="badge bg-dark">Application Cancelled by Us</span>';
                                                            if (!empty($completed['cancel_reason'])) {
                                                                echo '<br><small class="text-muted">' . htmlspecialchars($completed['cancel_reason']) . '</small>';
                                                            }
                                                        } elseif ($completed['is_cancelled_by_user'] && $completed['is_paid']) {
                                                            echo '<span class="badge bg-secondary">Application Cancelled by User</span>';
                                                            if (!empty($completed['cancel_reason'])) {
                                                                echo '<br><small class="text-muted">' . htmlspecialchars($completed['cancel_reason']) . '</small>';
                                                            }
                                                        } elseif ($completed['is_successful'] && $completed['is_paid']) {
                                                            echo '<span class="badge bg-primary">Order Complete</span>';
                                                        } else {
                                                            echo '<span class="badge bg-light text-dark">Unknown Status</span>';
                                                        }
                                                        ?>
                                                    </td>
                                                    <!-- <td><a target="_blank" class="btn btn-sm btn-golden rounded-pill" href="//support.fayyaztravels.com/">Open Ticket</a></td> -->
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <p class="card-text">No completed applications available.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Archived Applications -->
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <div class="card mt-3">
                            <div class="card-header bg-light fw-bold text-muted">
                                <i class="bi bi-archive-fill"></i> Archived Applications (<?= count($archived_orders); ?>)
                            </div>
                            <div class="card-body table-responsive">
                                <?php if (!empty($archived_orders)): ?>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Destination</th>
                                                <th>Ordered</th>
                                                <th>Last Edited</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($archived_orders as $archived): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($archived['order_id']); ?></td>
                                                    <td><?= htmlspecialchars(getCountryName($database, $archived['country_id']) ?? 'Unknown'); ?></td>
                                                    <td><?= timeAgo($archived['order_date']); ?></td>
                                                    <td><?= timeAgo(getLastUpdateTimestamp($database, $archived['order_id'])); ?></td>
                                                    <td>Archived</td>
                                                    <td>
                                                        <button onclick="unarchive(<?= htmlspecialchars(json_encode($archived['order_id'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP), ENT_QUOTES, 'UTF-8'); ?>, this)"
                                                            class="btn btn-sm rounded-pill btn-danger">
                                                            Unarchive
                                                        </button>

                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <p class="card-text">No archived applications available.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- ./Applications Page -->


    <!-- Footer -->
    <?php require 'components/Footer.php'; ?>
    <!-- Footer -->

    <?php
    // Output HTML scripts
    echo html_scripts(
        includeJQuery: false,
        includeBootstrap: true,
        customScripts: [],
        includeSwal: false,
        includeNotiflix: true
    );
    ?>
    <script>
        function sendToArchive(orderId, button) {
            button.disabled = true;
            const originalHTML = button.innerHTML;
            button.innerHTML = "Please wait...";

            fetch('api/v1/archive.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'HU': '<?= $hu; ?>'
                    },
                    body: JSON.stringify({
                        orderId
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok: ' + response.statusText);
                    return response.json();
                })
                .then(data => {
                    console.log('Success:', data);
                    Notiflix.Notify.success(data.success || 'Order archived successfully');
                    location.reload();
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    Notiflix.Notify.failure(error.message || 'Failed to archive order');
                })
                .finally(() => {
                    button.disabled = false;
                    button.innerHTML = originalHTML;
                });
        }

        function unarchive(orderId, button) {
            button.disabled = true;
            const originalHTML = button.innerHTML;
            button.innerHTML = "Please wait...";

            fetch('api/v1/unarchive.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'HU': '<?= $hu; ?>'
                    },
                    body: JSON.stringify({
                        orderId
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok: ' + response.statusText);
                    return response.json();
                })
                .then(data => {
                    console.log('Success:', data);
                    Notiflix.Notify.success(data.success || 'Order unarchived successfully');
                    location.reload();
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    Notiflix.Notify.failure(error.message || 'Failed to unarchive order');
                })
                .finally(() => {
                    button.disabled = false;
                    button.innerHTML = originalHTML;
                });
        }
    </script>


</body>

</html>