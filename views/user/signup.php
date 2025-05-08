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
// require 'PageCache.php'; // Include the PageCache class
require 'min.php';

// SEO
require 'vendor/autoload.php';

use Melbahja\Seo\Schema;
use Melbahja\Seo\Schema\Thing;
use Melbahja\Seo\MetaTags;

$schema = new Schema(
    new Thing('Organization', [
        'url'          => 'https://fayyaztravels.com/visa',
        'logo'         => 'https://fayyaztravels.com/uploads/images/main-logo.png',
        'contactPoint' => new Thing('ContactPoint', [
            'telephone'   => '+65 6235 2900',
            'contactType' => 'customer service'
        ])
    ])
);

// write schema for this web page    
$webPageSchema = new Schema(
    new Thing('WebPage', [
        'url' => 'https://fayyaztravels.com/visa/auth/signup',
        'name' => 'Sign Up',
        'description' => 'Create an account with Fayyaz Travels to apply for your visa online. Start your visa application process today!',
        'author' => new Thing('Organization', [
            'name' => 'Fayyaz Travels',
            'url' => 'https://fayyaztravels.com/visa'
        ])
    ])
);

// schema for breadcrumb
$breadcrumbSchema = new Schema(
    new Thing('BreadcrumbList', [
        'itemListElement' => [
            new Thing('ListItem', [
                'position' => 1,
                'name' => 'Home',
                'url' => 'https://fayyaztravels.com/visa'
            ]),
            new Thing('ListItem', [
                'position' => 2,
                'name' => 'Sign Up',
                'url' => 'https://fayyaztravels.com/visa/auth/signup'
            ])
        ]
    ])
);






$metatags = new MetaTags();

$metatags
    ->description('Create an account with Fayyaz Travels to apply for your visa online. Start your visa application process today!')
    ->meta('author', 'Fayyaz Travels')
    ->meta('robots', 'index, follow')
    ->image($url . '/og/index.php?title=' . urlencode('Signup') . '&subtitle=' . urlencode('Create an account with Fayyaz Travels to apply for your visa online. Start your visa application process today!') . '&gradient=' . urlencode('sunset_meadow'))
    ->canonical('https://fayyaztravels.com/visa/auth/signup');

// Convert both to strings and concatenate
$seo = $webPageSchema . "\n" . $breadcrumbSchema . "\n" . $schema . "\n" . $metatags;
// SEO END

// Output HTML head and scripts
echo html_head('Sign Up', null, true, [
    'assets/css/signup.css',
    'https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.0/build/css/intlTelInput.css'
], true, false, $seo);
?>

<!-- style -->

<style>
    .emoji-btn {
        font-size: 2rem;
        cursor: not-allowed;
        opacity: 0.5;
        transition: transform 0.2s ease;
    }

    .emoji-btn.ready {
        cursor: pointer;
        opacity: 1;
    }

    .emoji-btn:hover {
        transform: scale(1.2);
    }

    #captcha-status {
        margin-top: 10px;
        font-weight: bold;
    }

    /* Password toggle button styles */
    .input-group .btn-outline-secondary {
        border-color: #ced4da;
        color: #6c757d;
    }

    .input-group .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        color: #495057;
    }

    .form-text {
        font-size: 0.875rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }

    /* Password strength colors */
    .strength-weak {
        background-color: #dc3545;
    }

    .strength-medium {
        background-color: #ffc107;
    }

    .strength-strong {
        background-color: #28a745;
    }

    .strength-very-strong {
        background-color: #198754;
    }

    /* Password match status colors */
    .match-success {
        color: #28a745;
    }

    .match-error {
        color: #dc3545;
    }
</style>

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
                    <h4 class="text-brown mb-2">Sign Up</h4>
                    <p class="text-muted mb-4">Create your account to apply for your visa online</p>
                </div>

                <!-- Sign Up Form -->
                <form novalidate autocomplete="off" id="regForm">
                    <div class="row">
                        <!-- First Name and Last Name in a row -->
                        <div class="col-md-6 mb-3">
                            <label for="firstName" class="form-label">First Name</label>
                            <input type="text" name="fname" id="fname" class="form-control form-control-lg" required>
                            <div class="invalid-feedback">Please provide your first name.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input type="text" name="lname" id="lname" class="form-control form-control-lg" required>
                            <div class="invalid-feedback">Please provide your last name.</div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" name="email" id="email" class="form-control form-control-lg" required>
                        <div class="invalid-feedback">Please enter a valid email address.</div>
                    </div>

                    <!-- Mobile Number -->
                    <div class="mb-3">
                        <label for="mobile" class="form-label">Mobile Number</label>
                        <input type="tel" id="mobile" name="mobile" class="form-control form-control-lg" placeholder="Mobile Number" required>
                        <div class="invalid-feedback">Please provide a valid mobile number.</div>
                    </div>

                    <!-- WhatsApp Number Selection (Updated) -->
                    <div id="whatsappRadioContainer" class="mt-3" style="display: none;">
                        <label class="form-label">Is your WhatsApp number the same as your mobile number?</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="whatsappSame" id="whatsappYes" value="yes" checked>
                            <label class="form-check-label" for="whatsappYes">Yes</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="whatsappSame" id="whatsappNo" value="no">
                            <label class="form-check-label" for="whatsappNo">No</label>
                        </div>
                    </div>


                    <!-- WhatsApp Number (if different) -->
                    <div class="mb-3" id="whatsappContainer" style="display: none;">
                        <label for="whatsappNumber" class="form-label">WhatsApp Number</label>
                        <input type="tel" id="whatsappNumber" name="whatsappNumber" class="form-control form-control-lg">
                        <div class="invalid-feedback">Please provide a WhatsApp number.</div>
                    </div>


                    <!-- Telegram Username -->
                    <div class="mb-3" id="telegramContainer" style="display: none;">
                        <label for="telegramUsername" class="form-label">Telegram Username</label>
                        <input type="text" id="telegramUsername" name="telegramUsername" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control form-control-lg" required>
                            <button class="btn btn-light border" type="button" id="togglePassword">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-light border" type="button" id="suggestPassword" title="Generate secure password">
                                <i class="bi bi-lightbulb"></i>
                            </button>
                        </div>
                        <div class="form-text">Password must be at least 8 characters long and include uppercase, lowercase, number and special character.</div>
                        <div class="invalid-feedback">Please provide a password.</div>

                        <!-- Password Strength Meter -->
                        <div class="mt-2">
                            <div class="progress" style="height: 5px;">
                                <div id="password-strength-meter" class="progress-bar" role="progressbar" style="width: 0%"></div>
                            </div>
                            <small id="password-strength-text" class="form-text"></small>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="repeatPassword" class="form-label">Repeat Password</label>
                        <div class="input-group">
                            <input type="password" name="repeatPassword" id="repeatPassword" class="form-control form-control-lg" required>
                            <button class="btn btn-light border" type="button" id="toggleRepeatPassword">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback" id="passwordMismatchFeedback" style="display: none;">
                            Passwords do not match.
                        </div>
                        <small id="password-match-status" class="form-text"></small>
                    </div>

                    <div class="mb-3">
                        <label for="contactMethod" class="form-label">Choose Your Contact Preference</label>
                        <div class="row">
                            <div class="col-md-6">
                                <!-- In the Contact Preference section -->
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="contactMethod[]" id="contactWhatsApp" value="whatsapp">
                                    <label class="form-check-label" for="contactWhatsApp">WhatsApp</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="contactMethod[]" id="contactEmail" value="email">
                                    <label class="form-check-label" for="contactEmail">Email</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="contactMethod[]" id="contactTelegram" value="telegram" onchange="toggleTelegramField()">
                                    <label class="form-check-label" for="contactTelegram">Telegram</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="contactMethod[]" id="contactCall" value="call">
                                    <label class="form-check-label" for="contactCall">Call</label>
                                </div>
                            </div>
                        </div>
                        <div class="invalid-feedback" id="contactMethodFeedback" style="display: none;">Please select at least one contact method.</div>
                    </div>

                    <div class="divider my-4"></div>

                    <div class="mb-3 form-check">
                        <br>
                        <input type="checkbox" class="form-check-input" id="tos" required>
                        <label class="form-check-label" for="tos">I accept the <a href="#" data-bs-toggle="modal" data-bs-target="#tosModal">Terms of Service</a></label>

                        <!-- Modal for Terms of Service -->
                        <div class="modal fade" id="tosModal" tabindex="-1" aria-labelledby="tosModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="tosModalLabel">Terms of Service</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <iframe src="terms-of-service.html" style="width: 100%; height: 300px;" frameborder="0"></iframe>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="invalid-feedback">You must accept the Terms of Service.</div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="privacy" required>
                        <label class="form-check-label" for="privacy">I accept the <a href="#" data-bs-toggle="modal" data-bs-target="#privacyModal">Privacy Policy</a></label>

                        <!-- Modal for Privacy Policy -->
                        <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="privacyModalLabel">Privacy Policy</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <iframe src="privacy-policy.html" style="width: 100%; height: 300px;" frameborder="0"></iframe>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="invalid-feedback">You must accept the Privacy Policy.</div>
                    </div>
                    <input type="hidden" name="country_code" id="country_code">
                    <input type="hidden" name="country_code_alpha" id="country_code_alpha">
                    <input type="hidden" name="country_name" id="country_name">

                    <!-- captcha -->
                    <div class="card mb-2">
                        <div class="card-header">
                            <h5 class="card-text">Verify You're Human</h5>
                        </div>
                        <div class="card-body">
                            <p>Select the emoji that matches: <span id="target-emoji" class="fw-bold"></span></p>
                            <div class="d-flex gap-3 justify-content-center" id="emoji-options"></div>

                            <!-- Honeypot trap -->
                            <input type="text" name="website" style="display:none" autocomplete="off">

                            <!-- Hidden fields for server-side validation -->
                            <input type="hidden" name="selectedEmoji" id="selectedEmoji">
                            <input type="hidden" name="targetEmoji" id="targetEmoji">
                            <input type="hidden" name="captchaToken" value="<?= md5(time() . rand()) ?>">

                            <div id="captcha-status" class="text-danger"></div>

                        </div>
                    </div>

                    <!-- captcha -->
                    <button type="submit" id="submit-btn" class=" btn cta-button w-100 btn-lg rounded-pill p-2 plexFont fw-bold">Sign Up</button>
                </form>

                <div class="divider">
                    <span class="px-2 bg-white text-muted fw-bold">OR</span>
                </div>

                <?php if (isset($_GET['o'])): ?>
                    <p class="card-text fw-bold">Already signed up? <a href="auth/login?o=<?= htmlspecialchars($_GET['o']); ?>">Log in</a></p>
                <?php else: ?>
                    <p class="card-text fw-bold">Already signed up? <a href="auth/login">Log in</a></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php
// Output HTML scripts
echo html_scripts(
    includeJQuery: true,
    includeBootstrap: true,
    customScripts: [],
    includeSwal: false,
    includeNotiflix: true
);
?>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.0/build/js/intlTelInput.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script> -->

<script>
    const hu = '<?= $hu; ?>';
</script>
<script>
    /*  const toggleTelegramField = () => {
        toggleField(telegramCheckbox, telegramContainer);
        if (!telegramCheckbox.checked) telegramInput.value = "";
    };

    const toggleField = (trigger, container, showOnChecked = true) => {
        container.style.display = showOnChecked === trigger.checked ? "block" : "none";
    };

    // Updated WhatsApp toggle function using checkbox
    const toggleWhatsAppField = () => {
        toggleField(whatsappCheckbox, whatsappContainer);
        if (!whatsappCheckbox.checked) whatsappInput.value = ""; // Clear input when hidden
    };

    const form = document.getElementById("regForm");
    const whatsappCheckbox = document.getElementById("contactWhatsApp"); // Changed to checkbox
    const whatsappContainer = document.getElementById("whatsappContainer");
    const whatsappInput = document.getElementById("whatsappNumber");
    const telegramCheckbox = document.getElementById("contactTelegram");
    const telegramContainer = document.getElementById("telegramContainer");
    const telegramInput = document.getElementById("telegramUsername");

    document.addEventListener("DOMContentLoaded", () => {
        // Initial state setup
        toggleWhatsAppField();
        toggleTelegramField();

        // Event Listeners
        whatsappCheckbox.addEventListener("change", toggleWhatsAppField); // Listen to checkbox change
        telegramCheckbox.addEventListener("change", toggleTelegramField);

        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            form.classList.add("was-validated");

            if (!form.checkValidity() || form.password.value !== form.repeatPassword.value) {
                document.getElementById("passwordMismatchFeedback").style.display =
                    form.password.value !== form.repeatPassword.value ? "block" : "none";
                return;
            }

            const formData = new FormData(form);
            const jsonData = Object.fromEntries(formData.entries());

            // Updated validation for checkbox
            if (whatsappCheckbox.checked && !whatsappInput.value) {
                Notiflix.Notify.failure("Please provide a WhatsApp number.");
                return;
            }

            if (telegramCheckbox.checked && !telegramInput.value) {
                Notiflix.Notify.failure("Please provide a Telegram username.");
                return;
            }

            try {
                const res = await fetch("api/v1/signup", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(jsonData)
                });

                const data = await res.json();
                if (data.success) {
                    Notiflix.Notify.success(data.success);
                    form.reset();
                    form.classList.remove("was-validated");
                    toggleWhatsAppField();
                    toggleTelegramField();
                } else {
                    Notiflix.Notify.failure(data.error || "Signup failed. Please try again.");
                }
            } catch (err) {
                Notiflix.Notify.failure('An error occurred. Please try again. ' + err);
            }
        });
    });*/
</script>

<script>
    /*
    // WhatsApp Control Functions
    const handleWhatsAppSelection = () => {
        const showRadio = contactWhatsApp.checked;
        whatsappRadioContainer.style.display = showRadio ? 'block' : 'none';

        if (!showRadio) {
            whatsappYes.checked = true; // Reset to default
            whatsappContainer.style.display = 'none';
            whatsappNumber.value = '';
        }
    };

    const handleWhatsAppNumberVisibility = () => {
        whatsappContainer.style.display = whatsappNo.checked ? 'block' : 'none';
        if (!whatsappNo.checked) whatsappNumber.value = '';
    };

    // Telegram Control Function
    const toggleTelegramField = () => {
        telegramContainer.style.display = contactTelegram.checked ? 'block' : 'none';
        if (!contactTelegram.checked) telegramUsername.value = '';
    };

    // DOM Elements
    const form = document.getElementById("regForm");
    const contactWhatsApp = document.getElementById("contactWhatsApp");
    const whatsappRadioContainer = document.getElementById("whatsappRadioContainer");
    const whatsappYes = document.getElementById("whatsappYes");
    const whatsappNo = document.getElementById("whatsappNo");
    const whatsappContainer = document.getElementById("whatsappContainer");
    const whatsappNumber = document.getElementById("whatsappNumber");
    const contactTelegram = document.getElementById("contactTelegram");
    const telegramContainer = document.getElementById("telegramContainer");
    const telegramUsername = document.getElementById("telegramUsername");

    document.addEventListener("DOMContentLoaded", () => {
        // Initial setup
        handleWhatsAppSelection();
        handleWhatsAppNumberVisibility();
        toggleTelegramField();

        // Event listeners
        contactWhatsApp.addEventListener('change', handleWhatsAppSelection);
        whatsappYes.addEventListener('change', handleWhatsAppNumberVisibility);
        whatsappNo.addEventListener('change', handleWhatsAppNumberVisibility);
        contactTelegram.addEventListener('change', toggleTelegramField);

        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            form.classList.add("was-validated");

            // Password validation
            if (form.password.value !== form.repeatPassword.value) {
                document.getElementById("passwordMismatchFeedback").style.display = 'block';
                return;
            }

            // WhatsApp validation
            if (contactWhatsApp.checked && whatsappNo.checked && !whatsappNumber.value) {
                whatsappNumber.classList.add('is-invalid');
                Notiflix.Notify.failure("Please provide a WhatsApp number.");
                return;
            }

            // Telegram validation
            if (contactTelegram.checked && !telegramUsername.value) {
                telegramUsername.classList.add('is-invalid');
                Notiflix.Notify.failure("Please provide a Telegram username.");
                return;
            }

            // Form submission logic
            try {
                const formData = new FormData(form);
                const jsonData = Object.fromEntries(formData.entries());

                // Clear WhatsApp number if not needed
                if (!contactWhatsApp.checked || whatsappYes.checked) {
                    jsonData.whatsappNumber = '';
                }

                const res = await fetch("api/v1/signup", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(jsonData)
                });

                // Handle response
                const data = await res.json();
                if (data.success) {
                    Notiflix.Notify.success(data.success);
                    form.reset();
                    form.classList.remove("was-validated");
                    handleWhatsAppSelection();
                    toggleTelegramField();
                } else {
                    Notiflix.Notify.failure(data.error || "Signup failed. Please try again.");
                }
            } catch (err) {
                Notiflix.Notify.failure('An error occurred. Please try again. ' + err);
            }
        });
    });
    */
</script>


<script src="assets/js/intl.js"></script>

<script>
    // WhatsApp Control Functions
    const handleWhatsAppSelection = () => {
        const showRadio = contactWhatsApp.checked;
        whatsappRadioContainer.style.display = showRadio ? 'block' : 'none';

        if (!showRadio) {
            whatsappYes.checked = true; // Reset to default
            whatsappContainer.style.display = 'none';
            whatsappNumber.value = '';
        }
    };

    const handleWhatsAppNumberVisibility = () => {
        whatsappContainer.style.display = whatsappNo.checked ? 'block' : 'none';
        if (!whatsappNo.checked) whatsappNumber.value = '';
    };

    // Telegram Control Function
    const toggleTelegramField = () => {
        telegramContainer.style.display = contactTelegram.checked ? 'block' : 'none';
        if (!contactTelegram.checked) telegramUsername.value = '';
    };

    // DOM Elements
    const form = document.getElementById("regForm");
    const contactWhatsApp = document.getElementById("contactWhatsApp");
    const whatsappRadioContainer = document.getElementById("whatsappRadioContainer");
    const whatsappYes = document.getElementById("whatsappYes");
    const whatsappNo = document.getElementById("whatsappNo");
    const whatsappContainer = document.getElementById("whatsappContainer");
    const whatsappNumber = document.getElementById("whatsappNumber");
    const contactTelegram = document.getElementById("contactTelegram");
    const telegramContainer = document.getElementById("telegramContainer");
    const telegramUsername = document.getElementById("telegramUsername");
    const mobileNo = document.getElementById("mobile");
    const signupButton = document.getElementById("submit-btn"); // Get the signup button

    document.addEventListener("DOMContentLoaded", () => {
        // Initial setup
        whatsappRadioContainer.style.display = 'none'; // Initially hidden
        whatsappContainer.style.display = 'none'; // Initially hidden
        telegramContainer.style.display = 'none'; // Initially hidden

        handleWhatsAppSelection();
        handleWhatsAppNumberVisibility();
        toggleTelegramField();

        // Event listeners
        contactWhatsApp.addEventListener('change', handleWhatsAppSelection);
        whatsappYes.addEventListener('change', handleWhatsAppNumberVisibility);
        whatsappNo.addEventListener('change', handleWhatsAppNumberVisibility);
        contactTelegram.addEventListener('change', toggleTelegramField);

        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            form.classList.add("was-validated");

            // Disable signup button to prevent multiple requests
            signupButton.disabled = true;
            signupButton.innerText = "Signing Up...";

            // Password validation
            if (form.password.value !== form.repeatPassword.value) {
                document.getElementById("passwordMismatchFeedback").style.display = 'block';
                signupButton.disabled = false;
                signupButton.innerText = "Sign Up";
                return;
            }

            // WhatsApp validation
            if (contactWhatsApp.checked && whatsappNo.checked && !whatsappNumber.value) {
                whatsappNumber.classList.add('is-invalid');
                Notiflix.Notify.failure("Please provide a WhatsApp number.");
                signupButton.disabled = false;
                signupButton.innerText = "Sign Up";
                return;
            } else if (contactWhatsApp.checked && whatsappNo.checked && !/^\d{6,15}$/.test(whatsappNumber.value)) {
                whatsappNumber.classList.add('is-invalid');
                Notiflix.Notify.failure("Please enter a valid WhatsApp number (6-15 digits).");
                signupButton.disabled = false;
                signupButton.innerText = "Sign Up";
                return;
            } else {
                whatsappNumber.classList.remove('is-invalid');
            }

            // Telegram validation
            if (contactTelegram.checked && !telegramUsername.value) {
                telegramUsername.classList.add('is-invalid');
                Notiflix.Notify.failure("Please provide a Telegram username.");
                signupButton.disabled = false;
                signupButton.innerText = "Sign Up";
                return;
            } else {
                telegramUsername.classList.remove('is-invalid');
            }

            // Mobile Number Validation
            if (!mobileNo.value || !/^\d{6,15}$/.test(mobileNo.value)) {
                mobileNo.classList.add('is-invalid');
                Notiflix.Notify.failure("Please enter a valid mobile number (6-15 digits).");
                signupButton.disabled = false;
                signupButton.innerText = "Sign Up";
                return;
            } else {
                mobileNo.classList.remove('is-invalid');
            }


            // Form submission logic
            try {
                const formData = new FormData(form);
                const jsonData = Object.fromEntries(formData.entries());

                // Clear WhatsApp number if not needed
                if (!contactWhatsApp.checked || whatsappYes.checked) {
                    jsonData.whatsappNumber = '';
                }

                // Convert contactMethod[] to an array and check if at least one is selected
                const selectedMethods = Array.from(
                    document.querySelectorAll('input[name="contactMethod[]"]:checked')
                ).map(input => input.value);

                if (selectedMethods.length === 0) {
                    Notiflix.Notify.failure("Please select at least one contact method.");
                    signupButton.disabled = false;
                    signupButton.innerText = "Sign Up";
                    return;
                }

                jsonData.contactMethod = selectedMethods;

                const res = await fetch("api/v1/signup", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "HU": hu
                    },
                    body: JSON.stringify(jsonData)
                });

                // Handle response
                const data = await res.json();
                if (data.success) {
                    Notiflix.Notify.success(data.success);
                    form.reset();
                    form.classList.remove("was-validated");
                    handleWhatsAppSelection();
                    toggleTelegramField();

                    // move to login page if sign up completed
                    const params = new URLSearchParams(window.location.search);
                    const o = params.get("o");
                    location.href = o ? `auth/login?o=${encodeURIComponent(o)}` : "auth/login?through=signup";

                } else {
                    Notiflix.Notify.failure(data.error || "Signup failed. Please try again.");
                }
            } catch (err) {
                Notiflix.Notify.failure('An error occurred. Please try again. ' + err);
            } finally {
                // Re-enable signup button after request completion
                signupButton.disabled = false;
                signupButton.innerText = "Sign Up";
            }
        });
    });
</script>


<script>
    fetchCountryCode((countryCode) => {
        // console.log("User's country code:", countryCode);

        // Wait for calling code to be available
        setTimeout(() => {
            // console.log("User's calling code:", window.userCallingCode); // Get calling code after fetch completes

            if (window.userCallingCode) {
                document.getElementById("country_code").value = window.userCallingCode;
            } else {
                console.error("Calling code is undefined, using default: +65");
                document.getElementById("country_code").value = "+65"; // Fallback to default
            }

            if (window.userCountryCode) {
                document.getElementById("country_code_alpha").value = window.userCountryCode;
            } else {
                console.error("Country code is undefined, using default: sg");
                document.getElementById("country_code_alpha").value = "sg"; // Fallback to default
            }

            if (window.userCountryName) {
                document.getElementById("country_name").value = window.userCountryName;
            } else {
                console.error("Country name is undefined, using default: Singapore");
                document.getElementById("country_name").value = "Singapore"; // Fallback to default
            }
        }, 500); // Delay to ensure callingCode is set
    });
</script>

<script>
    document.getElementById("submit-btn").disabled = true;
    const emojis = ["🐶", "🐱", "🐭", "🐹", "🐰", "🦊", "🐻", "🐼", "🐸", "🐵", "🐤", "🐧", "🐦", "🐺", "🐗", "🦁", "🐯", "🐴", "🦒", "🐘"];
    let target = "";
    let selected = "";

    function generateCaptcha() {
        const shuffled = [...emojis].sort(() => 0.5 - Math.random()).slice(0, 5);
        target = shuffled[Math.floor(Math.random() * shuffled.length)];
        document.getElementById("target-emoji").textContent = target;
        document.getElementById("targetEmoji").value = target;

        const container = document.getElementById("emoji-options");
        container.innerHTML = "";
        shuffled.forEach(emoji => {
            const span = document.createElement("span");
            span.textContent = emoji;
            span.classList.add("emoji-btn");
            container.appendChild(span);
        });

        document.getElementById("captcha-status").textContent = "Wait a sec…";

        // Delay interaction by 2 seconds
        setTimeout(() => {
            document.querySelectorAll(".emoji-btn").forEach(btn => {
                btn.classList.add("ready");
                btn.addEventListener("click", () => handleEmojiClick(btn.textContent, btn));
            });
            document.getElementById("captcha-status").textContent = "";
        }, 2000);
    }

    function handleEmojiClick(emoji, element) {
        selected = emoji;
        document.querySelectorAll(".emoji-btn").forEach(btn => btn.classList.remove("border", "border-primary"));
        element.classList.add("border", "border-primary");
        document.getElementById("selectedEmoji").value = selected;

        if (selected === target) {
            document.getElementById("captcha-status").textContent = "✔ Correct!";
            document.getElementById("captcha-status").classList.replace("text-danger", "text-success");
            document.getElementById("submit-btn").disabled = false;
        } else {
            document.getElementById("captcha-status").textContent = "✘ Wrong emoji!";
            document.getElementById("captcha-status").classList.replace("text-success", "text-danger");
            document.getElementById("submit-btn").disabled = true;
        }
    }

    generateCaptcha();
</script>

<script>
    // Password visibility toggle functionality
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('togglePassword');
        const toggleRepeatPassword = document.getElementById('toggleRepeatPassword');
        const password = document.getElementById('password');
        const repeatPassword = document.getElementById('repeatPassword');
        const suggestPasswordBtn = document.getElementById('suggestPassword');
        const strengthMeter = document.getElementById('password-strength-meter');
        const strengthText = document.getElementById('password-strength-text');
        const matchStatus = document.getElementById('password-match-status');

        function checkPasswordStrength(password) {
            let strength = 0;
            const feedback = [];

            // Length check
            if (password.length >= 8) {
                strength += 25;
            } else {
                feedback.push('At least 8 characters');
            }

            // Uppercase check
            if (/[A-Z]/.test(password)) {
                strength += 25;
            } else {
                feedback.push('uppercase letter');
            }

            // Lowercase check
            if (/[a-z]/.test(password)) {
                strength += 25;
            } else {
                feedback.push('lowercase letter');
            }

            // Number check
            if (/[0-9]/.test(password)) {
                strength += 12.5;
            } else {
                feedback.push('number');
            }

            // Special character check
            if (/[^A-Za-z0-9]/.test(password)) {
                strength += 12.5;
            } else {
                feedback.push('special character');
            }

            // Update strength meter
            strengthMeter.style.width = strength + '%';
            strengthMeter.className = 'progress-bar';

            // Set color based on strength
            if (strength < 50) {
                strengthMeter.classList.add('strength-weak');
                strengthText.textContent = 'Weak password. Add: ' + feedback.join(', ');
            } else if (strength < 75) {
                strengthMeter.classList.add('strength-medium');
                strengthText.textContent = 'Medium strength. Add: ' + feedback.join(', ');
            } else if (strength < 100) {
                strengthMeter.classList.add('strength-strong');
                strengthText.textContent = 'Strong password';
            } else {
                strengthMeter.classList.add('strength-very-strong');
                strengthText.textContent = 'Very strong password!';
            }
        }

        function checkPasswordMatch() {
            const match = password.value === repeatPassword.value;
            if (password.value && repeatPassword.value) {
                if (match) {
                    matchStatus.textContent = '✓ Passwords match';
                    matchStatus.className = 'form-text match-success';
                } else {
                    matchStatus.textContent = '✗ Passwords do not match';
                    matchStatus.className = 'form-text match-error';
                }
            } else {
                matchStatus.textContent = '';
            }
        }

        function togglePasswordVisibility(inputField, button) {
            const type = inputField.getAttribute('type') === 'password' ? 'text' : 'password';
            inputField.setAttribute('type', type);

            // Toggle eye icon
            const icon = button.querySelector('i');
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        }

        function generateSecurePassword() {
            const uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            const lowercase = 'abcdefghijklmnopqrstuvwxyz';
            const numbers = '0123456789';
            const special = '!@#$%^&*()_+-=[]{}|;:,.<>?';

            // Ensure at least one character from each category
            let password = '';
            password += uppercase[Math.floor(Math.random() * uppercase.length)];
            password += lowercase[Math.floor(Math.random() * lowercase.length)];
            password += numbers[Math.floor(Math.random() * numbers.length)];
            password += special[Math.floor(Math.random() * special.length)];

            // Add more random characters to reach desired length
            const allChars = uppercase + lowercase + numbers + special;
            for (let i = 0; i < 8; i++) {
                password += allChars[Math.floor(Math.random() * allChars.length)];
            }

            // Shuffle the password
            password = password.split('').sort(() => 0.5 - Math.random()).join('');

            return password;
        }

        function suggestNewPassword() {
            const newPassword = generateSecurePassword();
            password.value = newPassword;
            repeatPassword.value = newPassword;

            // Update strength meter and match status
            checkPasswordStrength(newPassword);
            checkPasswordMatch();

            // Show success notification
            Notiflix.Notify.success('New secure password generated!');

            // Show the password briefly
            password.type = 'text';
            repeatPassword.type = 'text';

            // Update eye icons
            const passwordIcon = togglePassword.querySelector('i');
            const repeatPasswordIcon = toggleRepeatPassword.querySelector('i');
            passwordIcon.classList.remove('bi-eye');
            passwordIcon.classList.add('bi-eye-slash');
            repeatPasswordIcon.classList.remove('bi-eye');
            repeatPasswordIcon.classList.add('bi-eye-slash');

            // Create or update copy button
            let copyButton = document.getElementById('copyPasswordBtn');
            if (!copyButton) {
                copyButton = document.createElement('button');
                copyButton.id = 'copyPasswordBtn';
                copyButton.className = 'btn btn-light border w-100 mt-2';
                copyButton.innerHTML = '<i class="bi bi-clipboard"></i> Copy Password';
                copyButton.style.transition = 'all 0.2s ease';

                // Add click handler for copy button
                copyButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    navigator.clipboard.writeText(password.value).then(() => {
                        copyButton.innerHTML = '<i class="bi bi-check"></i> Copied!';
                        copyButton.classList.add('btn-success');
                        copyButton.classList.remove('btn-light');
                        setTimeout(() => {
                            copyButton.innerHTML = '<i class="bi bi-clipboard"></i> Copy Password';
                            copyButton.classList.remove('btn-success');
                            copyButton.classList.add('btn-light');
                        }, 2000);
                    });
                });

                // Add copy button after the repeat password input group
                const repeatPasswordInputGroup = repeatPassword.parentElement;
                repeatPasswordInputGroup.parentElement.appendChild(copyButton);
            }

            // Hide password after 3 seconds but keep copy button
            setTimeout(() => {
                password.type = 'password';
                repeatPassword.type = 'password';
                passwordIcon.classList.remove('bi-eye-slash');
                passwordIcon.classList.add('bi-eye');
                repeatPasswordIcon.classList.remove('bi-eye-slash');
                repeatPasswordIcon.classList.add('bi-eye');
            }, 3000);
        }

        // Add event listeners for real-time validation
        password.addEventListener('input', () => {
            checkPasswordStrength(password.value);
            checkPasswordMatch();
        });

        repeatPassword.addEventListener('input', checkPasswordMatch);

        togglePassword.addEventListener('click', () => togglePasswordVisibility(password, togglePassword));
        toggleRepeatPassword.addEventListener('click', () => togglePasswordVisibility(repeatPassword, toggleRepeatPassword));
        suggestPasswordBtn.addEventListener('click', suggestNewPassword);
    });
</script>



</body>

</html>