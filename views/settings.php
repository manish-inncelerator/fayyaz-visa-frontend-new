<?php
defined('BASE_DIR') || die('Direct access denied');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session only if not already active
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Debugging - Check if session is working
if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login');
    exit();
}



// Include required files
require 'inc/html_head.php';
require 'inc/html_foot.php';
require 'database.php';
require 'min.php';

// Output HTML head and scripts
echo html_head('Settings', null, true, null, true);
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

    <!-- Applications -->
    <main class="container my-2 flex-grow-1 overflow-auto">
        <div class="row">
            <div class="col-12 mb-2">
                <h1><b>Settings</b></h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-golden"><i class="bi bi-house"></i> Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Settings</li>
                    </ol>
                </nav>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header fw-bold text-muted">
                        <i class="bi bi-gear-fill"></i> Settings
                    </div>
                    <div class="card-body">
                        <h2 class="text-muted"><b>Change Password</b></h2>
                        <hr>
                        <form novalidate id="passChanger">
                            <!-- Change Password Section -->
                            <div class="row mb-3">
                                <div class="col-12 col-xl-2 col-xxl-2">
                                    <label for="currentPassword" class="form-label">Current Password</label>
                                </div>
                                <div class="col-12 col-xl-10 col-xxl-10">
                                    <input type="password" class="form-control" id="currentPassword" name="currentPassword" placeholder="Enter your current password" required>
                                    <div class="invalid-feedback">Please enter your current password.</div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12 col-xl-2 col-xxl-2">
                                    <label for="newPassword" class="form-label">New Password</label>
                                </div>
                                <div class="col-12 col-xl-10 col-xxl-10">
                                    <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="Enter your new password" required minlength="6">
                                    <div class="invalid-feedback">New password must be at least 6 characters long.</div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12 col-xl-2 col-xxl-2">
                                    <label for="confirmNewPassword" class="form-label">Confirm New Password</label>
                                </div>
                                <div class="col-12 col-xl-10 col-xxl-10">
                                    <input type="password" class="form-control" id="confirmNewPassword" name="confirmNewPassword" placeholder="Confirm your new password" required>
                                    <div class="invalid-feedback">Please confirm your new password.</div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12 col-xl-2 col-xxl-2"></div>
                                <div class="col-12 col-xl-10 col-xxl-10">
                                    <button type="submit" class="btn btn-blue fw-bold"><i class="bi bi-key"></i> Change Password</button>
                                </div>
                            </div>
                        </form>

                        <!-- Delete Account Section -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <hr> <!-- Horizontal line to separate sections -->
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 col-xl-2 col-xxl-2">
                                <label class="form-label text-danger fw-bold">Delete Account</label>
                            </div>
                            <div class="col-12 col-xl-10 col-xxl-10">
                                <p class="text-muted fw-bold">This action is irreversible. All your data will be permanently deleted.</p>
                                <button type="button" class="btn btn-danger fw-bold" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                    <i class="bi bi-trash"></i> Delete Account
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- ./Applications -->

    <!-- Delete Account Confirmation Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="deleteAccountModalLabel">Delete your account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete your account? This action cannot be undone.</p>
                    <p class="text-muted">To confirm, enter your password below:</p>
                    <input type="password" class="form-control" id="deleteAccountPassword" placeholder="Enter your password">
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button> -->
                    <button type="button" class="btn btn-danger" id="confirmDeleteAccount">
                        <i class="bi bi-trash"></i> Delete Account
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php require 'components/Footer.php'; ?>

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
    <!-- change password -->
    <script>
        document.querySelector('#passChanger').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            // Bootstrap validation
            const form = event.target;
            if (!form.checkValidity()) {
                event.stopPropagation();
                form.classList.add('was-validated');
                return;
            }

            // Get form data
            const currentPassword = document.getElementById('currentPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmNewPassword = document.getElementById('confirmNewPassword').value;

            // Validate new password and confirmation match
            if (newPassword !== confirmNewPassword) {
                // alert('New password and confirmation do not match.');
                Notiflix.Notify.failure('New password and confirmation password do not match.');
                return;
            }

            // Prepare data to send as JSON
            const data = {
                currentPassword: currentPassword,
                newPassword: newPassword,
                confirmNewPassword: confirmNewPassword
            };

            // Send data using fetch API
            fetch('api/v1/change-password.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json', // Set the content type to JSON
                        'HU': '<?= $hu; ?>' // Add the custom header 'HU'
                    },
                    body: JSON.stringify(data) // Convert data to JSON string
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // alert('Password changed successfully!');

                        Notiflix.Notify.success('Password changed successfully!');
                        form.reset();
                        form.classList.remove('was-validated');
                    } else {
                        // alert('Error: ' + data.message);

                        Notiflix.Notify.failure('Error:' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // alert('An error occurred while changing the password.');

                    Notiflix.Notify.failure('An error occurred while changing the password.');
                });
        });
    </script>
    <!-- ./change password -->

    <!-- Delete Account -->
    <script>
        document.getElementById("confirmDeleteAccount").addEventListener("click", function() {
            const password = document.getElementById("deleteAccountPassword").value;

            if (!password) {
                alert("Please enter your password to confirm deletion.");
                return;
            }

            fetch("api/v1/deleteAccount", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "hu": "<?= $hu; ?>"
                    },
                    body: JSON.stringify({
                        password: password
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // alert("Your account has been deleted successfully.");

                        Notiflix.Notify.failure(data.message || 'Your accound has been deleted succesfully.');
                        window.location.href = "logout"; // Redirect user after account deletion
                    } else {
                        // alert("Error: " + data.error);
                        Notiflix.Notify.failure(data.error);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    // alert("An error occurred. Please try again later.");
                    Notiflix.Notify.failure(error || 'An error occurred. Please try again later.');

                });
        });
    </script>
    <!-- ./Delete Account -->
</body>

</html>