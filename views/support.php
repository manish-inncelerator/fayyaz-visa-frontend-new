<?php
defined('BASE_DIR') || die('Direct access denied');

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
echo html_head('Support', null, true, false, true);

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
    $result = $database->get("countries", "country_name", [
        "id" => $country_id
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

    <!-- Support Page -->
    <main class="container my-2 flex-grow-1 overflow-auto">
        <div class="row">
            <div class="col-12 mb-2">
                <h1><b>Support</b> <!-- Button to Open Modal -->
                    <a href="#" class="float-end btn btn-dark btn-lg rounded-pill" data-bs-toggle="modal" data-bs-target="#newTicketModal">
                        <i class="bi bi-plus-circle"></i> Open a New Ticket
                    </a>
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/" class="text-decoration-none text-golden">
                                <i class="bi bi-house"></i> Home
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Support</li>
                    </ol>
                </nav>
            </div>

            <div class="col-12">
                <!-- Tabs Navigation -->
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active link-golden text-decoration-none" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
                            <i class="bi bi-file-earmark"></i> Open Tickets
                        </button>
                        <button class="nav-link link-golden text-decoration-none" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">
                            <i class="bi bi-archive"></i> Closed Tickets
                        </button>
                    </div>
                </nav>

                <!-- Tab Content -->
                <div class="tab-content" id="nav-tabContent">
                    <!-- All Applications -->
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">

                    </div>

                    <!-- Archived Applications -->
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">

                    </div>
    </main>
    <!-- ./Applications Page -->

    <!-- New Ticket Modal -->
    <div class="modal fade" id="newTicketModal" tabindex="-1" aria-labelledby="newTicketModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newTicketModalLabel">Create New Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="ticketForm">
                        <div class="mb-3">
                            <label for="ticketTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="ticketTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="ticketDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="ticketDescription" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="ticketCategory" class="form-label">Category</label>
                            <select class="form-select" id="ticketCategory">
                                <option value="General">General</option>
                                <option value="Technical">Technical</option>
                                <option value="Billing">Billing</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Ticket</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



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
    <!--Start a new ticket -->
    <script>
        document.getElementById("ticketForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent form submission

            // Retrieve form values
            const title = document.getElementById("ticketTitle").value;
            const description = document.getElementById("ticketDescription").value;
            const category = document.getElementById("ticketCategory").value;

            // Example: Sending data to the backend (Modify as needed)
            fetch('api/v1/create-ticket', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'HU': '<?= $hu; ?>'
                    },
                    body: JSON.stringify({
                        title,
                        description,
                        category
                    })
                }).then(response => response.json())
                .then(data => {
                    alert("Ticket created successfully!"); // Success message
                    document.getElementById("ticketForm").reset(); // Reset form
                    var modal = new bootstrap.Modal(document.getElementById('newTicketModal'));
                    modal.hide(); // Close modal after submission
                }).catch(error => console.error("Error:", error));
        });
    </script>
</body>

</html>