<?php

defined('BASE_DIR') || die('Direct access denied');

// Redirect to home if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: ../home');
    exit;
}

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include required files
require 'inc/html_head.php';
require 'inc/html_foot.php';
require 'database.php';
require 'PageCache.php'; // Include the PageCache class
require 'min.php';

// Output HTML head and scripts
echo html_head('Sign Up', null, true, [
    'assets/css/signup.css',
    'https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.0/build/css/intlTelInput.css',
    'https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css'
], true);
?>

<!-- Navbar -->
<?php require 'components/SimpleNavbar.php'; ?>
<!-- ./Navbar -->

<!-- Signup Section -->
<section class="container d-flex justify-content-center align-items-center">
    <div class="row">
        <div class="col-12">
            <div class="form-container my-2 rounded-3 bg-white">
                <!-- Icon and Header -->
                <div class="text-center">
                    <div class="icon-circle mx-auto">
                        <i class="bi bi-person fs-4"></i>
                    </div>
                    <h4 class="text-brown mb-2">Visas on time</h4>
                    <p class="text-muted mb-4">And sign ups in no time.</p>
                </div>

                <!-- Sign Up Form -->
                <form novalidate autocomplete="off" id="regForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="firstName" class="form-label">First Name</label>
                            <input type="text" name="fname" id="fname" class="form-control" required>
                            <div class="invalid-feedback">Please provide your first name.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input type="text" name="lname" id="lname" class="form-control" required>
                            <div class="invalid-feedback">Please provide your last name.</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                        <div class="invalid-feedback">Please enter a valid email address.</div>
                    </div>

                    <div class="mb-3">
                        <label for="mobile" class="form-label">Mobile Number</label>
                        <input type="tel" id="mobile" name="mobile" class="form-control" required>
                        <div class="invalid-feedback">Please provide a valid mobile number.</div>
                    </div>

                    <div class="mb-3">
                        <label for="nationality" class="form-label">Nationality</label>
                        <input type="text" id="nationality" name="nationality" class="form-control" required>
                        <div class="invalid-feedback">Please enter your nationality.</div>
                    </div>

                    <div class="mb-3">
                        <label for="dob" class="form-label">Date of Birth</label>
                        <input type="date" id="dob" name="dob" class="form-control" required>
                        <div class="invalid-feedback">Please enter your date of birth.</div>
                    </div>

                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select id="gender" name="gender" class="form-control" required>
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                        <div class="invalid-feedback">Please select your gender.</div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                        <div class="invalid-feedback">Please enter a password.</div>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                        <div class="invalid-feedback">Passwords do not match.</div>
                    </div>

                    <div id="whatsappRadioContainer" class="mt-3">
                        <label class="form-label">Is WhatsApp number the same as Mobile?</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="whatsappSame" id="whatsappYes" value="yes">
                            <label class="form-check-label" for="whatsappYes">Yes</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="whatsappSame" id="whatsappNo" value="no">
                            <label class="form-check-label" for="whatsappNo">No</label>
                        </div>
                    </div>

                    <div class="mb-3" id="whatsappContainer" style="display: none;">
                        <label for="whatsappNumber" class="form-label">WhatsApp Number</label>
                        <input type="tel" id="whatsappNumber" name="whatsappNumber" class="form-control">
                    </div>

                    <div class="mb-3">
                        <input type="checkbox" class="form-check-input" id="tos" required>
                        <label class="form-check-label" for="tos">I accept the <a href="#" data-bs-toggle="modal" data-bs-target="#tosModal">Terms of Service</a></label>
                    </div>

                    <button type="submit" class="btn cta-button w-100 btn-lg rounded-pill p-2 fw-bold">Sign Up</button>
                </form>
            </div>
        </div>
    </div>
</section>

</body>

</html>