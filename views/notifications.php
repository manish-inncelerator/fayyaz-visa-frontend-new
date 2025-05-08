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
echo html_head('My Notifications', null, true, false, true);



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
// Pagination settings
$limit = 10;
if (isset($_GET['limit'])) {
    // Handle cases where limit might be an array
    if (is_array($_GET['limit'])) {
        $limit = 10; // Default value if array is provided
    } else {
        $limit = is_numeric($_GET['limit']) ? (int)$_GET['limit'] : 10;
    }
}

$allowed_limits = [5, 10, 20, 50];
if (!in_array($limit, $allowed_limits, true)) {
    $limit = 10;
}

$page = 1;
if (isset($_GET['page'])) {
    // Handle cases where page might be an array
    if (is_array($_GET['page'])) {
        $page = 1; // Default value if array is provided
    } else {
        $page = is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    }
}
$offset = ($page - 1) * $limit;

// Count total notifications
$notification_count = $database->count('notifications', [
    'customer_id' => $_SESSION['user_id']
]);

// Fetch paginated notifications sorted by newest first
$notifications = $database->select('notifications', '*', [
    'customer_id' => $_SESSION['user_id'],
    "ORDER" => ["created_at" => "DESC"], // Order by latest
    "LIMIT" => [$offset, $limit]
]);

// Calculate total pages for pagination
$total_pages = ceil($notification_count / $limit);

// Calculate start and end page numbers for pagination display
$start_page = max(1, $page - 2);
$end_page = min($total_pages, $page + 2);

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

    <!-- Notifications Page -->
    <main class="container my-4 flex-grow-1 overflow-auto">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="fw-bold mb-1"></i>My Notifications</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="/visa/" class="text-decoration-none text-golden">
                                        <i class="bi bi-house"></i> Home
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">My Notifications</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="badge bg-golden text-white p-2">
                        <i class="bi bi-bell"></i> <?= $notification_count; ?> Notifications
                    </div>
                </div>

                <div class="card border-0">
                    <div class="card-body p-0">
                        <?php if (!empty($notifications)): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($notifications as $notification): ?>
                                    <div class="list-group-item list-group-item-action <?= $notification['is_seen'] == 0 ? 'bg-light' : ''; ?> p-3" data-notification-id="<?= $notification['id']; ?>">
                                        <div class="d-flex w-100 justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center mb-2">
                                                    <?php if ($notification['is_seen'] == 0): ?>
                                                        <span class="badge bg-primary me-2">New</span>
                                                    <?php endif; ?>
                                                    <small class="text-muted">
                                                        <i class="bi bi-clock"></i> <?= timeAgo($notification['created_at']); ?>
                                                    </small>
                                                </div>
                                                <p class="mb-2 <?= $notification['is_seen'] == 0 ? 'fw-bold' : ''; ?>">
                                                    <?= htmlspecialchars($notification['notification_message']); ?>
                                                </p>
                                                <div class="d-flex gap-2 mt-3">
                                                    <?php if (!empty($notification['link'])): ?>
                                                        <a href="<?= htmlspecialchars($notification['link']); ?>" class="btn btn-sm btn-golden rounded-pill">
                                                            <i class="bi bi-eye"></i> View Details
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if ($notification['is_seen'] == 0): ?>
                                                        <button class="btn btn-sm btn-success rounded-pill mark-read-btn">
                                                            <i class="bi bi-check2"></i> Mark as Read
                                                        </button>
                                                    <?php endif; ?>
                                                    <button class="btn btn-sm btn-danger rounded-pill delete-btn">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-between align-items-center mt-4 px-3 pb-3">
                                <div class="d-flex align-items-center">
                                    <span class="text-muted me-2">Show:</span>
                                    <select class="form-select form-select-sm border-golden" style="width: auto; min-width: 120px;" onchange="window.location.href='notifications?page=1&limit=' + this.value">
                                        <?php foreach ($allowed_limits as $allowed_limit): ?>
                                            <option value="<?= $allowed_limit ?>" <?= $limit == $allowed_limit ? 'selected' : '' ?>>
                                                <?= $allowed_limit ?> per page
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <?php if ($total_pages > 1): ?>
                                    <nav>
                                        <ul class="pagination justify-content-center mb-0">
                                            <!-- First Page -->
                                            <?php if ($page > 1): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="notifications?page=1&limit=<?= $limit ?>" title="First Page">
                                                        <i class="bi bi-chevron-double-left"></i>
                                                    </a>
                                                </li>
                                            <?php endif; ?>

                                            <!-- Previous Page -->
                                            <?php
                                            // Ensure $page is an integer before using it in calculations
                                            $prev_page = is_array($page) ? 1 : max(1, (int)$page - 1);
                                            $current_limit = is_array($limit) ? 10 : (int)$limit;
                                            ?>

                                            <?php if ((int)$page > 1): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="notifications?page=<?= $prev_page ?>&limit=<?= $current_limit ?>" title="Previous Page">
                                                        <i class="bi bi-chevron-left"></i>
                                                    </a>
                                                </li>
                                            <?php endif; ?>

                                            <!-- Page Numbers -->
                                            <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                                                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                                    <a class="page-link" href="notifications?page=<?= $i ?>&limit=<?= $limit ?>">
                                                        <?= $i ?>
                                                    </a>
                                                </li>
                                            <?php endfor; ?>

                                            <!-- Next Page -->
                                            <?php if ($page < $total_pages): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="notifications?page=<?= $page + 1 ?>&limit=<?= $limit ?>" title="Next Page">
                                                        <i class="bi bi-chevron-right"></i>
                                                    </a>
                                                </li>
                                            <?php endif; ?>

                                            <!-- Last Page -->
                                            <?php if ($page < $total_pages): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="notifications?page=<?= $total_pages ?>&limit=<?= $limit ?>" title="Last Page">
                                                        <i class="bi bi-chevron-double-right"></i>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </nav>
                                <?php else: ?>
                                    <div></div> <!-- Empty div for spacing -->
                                <?php endif; ?>
                                <div class="text-muted">
                                    Showing <?= $offset + 1 ?>-<?= min($offset + $limit, $notification_count) ?> of <?= $notification_count ?> notifications
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="bi bi-bell-slash display-1 text-muted mb-3"></i>
                                <p class="text-muted mb-0">No notifications available.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- ./Notifications Page -->

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

    <style>
        .form-select-sm {
            padding: 0.25rem 2rem 0.25rem 0.5rem;
            font-size: 0.875rem;
            border-radius: 0.25rem;
            background-color: #fff;
            cursor: pointer;
        }

        .form-select-sm:focus {
            border-color: #d4af37;
            box-shadow: 0 0 0 0.25rem rgba(212, 175, 55, 0.25);
        }

        .form-select-sm option {
            padding: 0.5rem;
        }
    </style>

    <script>
        // Mark notification as read when clicked
        document.querySelectorAll('.mark-read-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                const notificationItem = this.closest('.list-group-item');
                const notificationId = notificationItem.dataset.notificationId;

                fetch('api/v1/markNotificationRead.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'HU': '<?= $hu; ?>'
                        },
                        body: JSON.stringify({
                            notification_id: notificationId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            notificationItem.classList.remove('bg-light');
                            this.remove();
                            Notiflix.Notify.success('Notification marked as read');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Notiflix.Notify.failure('Failed to mark notification as read');
                    });
            });
        });

        // Delete notification
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                const notificationItem = this.closest('.list-group-item');
                const notificationId = notificationItem.dataset.notificationId;

                Notiflix.Confirm.show(
                    'Delete Notification',
                    'Are you sure you want to delete this notification?',
                    'Yes',
                    'No',
                    function() {
                        fetch('api/v1/deleteNotification.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'HU': '<?= $hu; ?>'
                                },
                                body: JSON.stringify({
                                    notification_id: notificationId
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    notificationItem.remove();
                                    Notiflix.Notify.success('Notification deleted successfully');
                                    // Reload page after successful deletion
                                    window.location.reload();
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Notiflix.Notify.failure('Failed to delete notification');
                            });
                    }
                );
            });
        });
    </script>
</body>

</html>