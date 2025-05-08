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
        cursor: pointer;
        opacity: 0.8;
        transition: all 0.3s ease;
        background: none;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 10px 15px;
        margin: 5px;
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .emoji-btn:hover {
        transform: scale(1.1);
        opacity: 1;
        border-color: #007bff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .emoji-btn.ready {
        background-color: #e8f4ff;
        border-color: #007bff;
        opacity: 1;
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
    }

    #emoji-options {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
        padding: 20px 0;
    }

    .target-emoji-container {
        text-align: center;
        margin: 20px 0;
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .target-emoji-text {
        font-size: 1.2rem;
        color: #6c757d;
        margin-bottom: 10px;
    }

    #target-emoji {
        font-size: 3rem;
        display: inline-block;
        animation: pulse 2s infinite;
        background-color: white;
        padding: 15px;
        border-radius: 50%;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }

        100% {
            transform: scale(1);
        }
    }

    #captcha-status {
        margin-top: 15px;
        font-weight: bold;
        font-size: 1.1rem;
        text-align: center;
        padding: 10px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .text-success {
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745;
    }

    .text-danger {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }

    .math-captcha-container {
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 10px;
    }

    #math-captcha-question {
        font-weight: bold;
        color: #333;
    }

    #captcha-status {
        margin-top: 10px;
        font-weight: bold;
    }

    .text-success {
        color: #28a745;
    }

    .text-danger {
        color: #dc3545;
    }

    .text-captcha-container {
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 10px;
    }

    #captcha-text {
        font-weight: bold;
        color: #333;
        letter-spacing: 2px;
        background-color: #e9ecef;
        padding: 5px 10px;
        border-radius: 5px;
    }

    #captcha-status {
        margin-top: 10px;
        font-weight: bold;
    }

    .text-success {
        color: #28a745;
    }

    .text-danger {
        color: #dc3545;
    }

    .checkbox-captcha-container {
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 10px;
    }

    #captcha-status {
        font-weight: bold;
    }

    .text-success {
        color: #28a745;
    }

    .text-danger {
        color: #dc3545;
    }

    .form-check {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .form-check-input {
        width: 1.2em;
        height: 1.2em;
        margin-top: 0;
        margin-right: 0;
        cursor: pointer;
    }

    .form-check-label {
        font-size: 1rem;
        cursor: pointer;
        margin-bottom: 0;
    }

    #checkbox-timer {
        font-size: 0.9rem;
        margin-top: 0.5rem;
    }

    /* Contact preference checkboxes container */
    #contactMethodContainer {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: 0.5rem;
    }

    /* Terms and Privacy checkboxes */
    .terms-checkbox {
        margin-bottom: 1rem;
    }

    .terms-checkbox .form-check {
        padding-left: 0;
    }

    .terms-checkbox .form-check-label {
        font-size: 0.95rem;
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
                    <h4 class="text-brown mb-2">Signup</h4>
                    <p class="text-muted mb-4">Create an account to apply for your visa online.</p>
                </div>

                <!-- Sign Up Form -->
                <form novalidate autocomplete="off" id="regForm">
                    <div class="row">
                        <!-- First Name and Last Name in a row -->
                        <div class="col-md-6 mb-3">
                            <label for="firstName" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="fname" id="fname" class="form-control" required>
                            <div class="invalid-feedback">Please provide your first name.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lastName" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="lname" id="lname" class="form-control" required>
                            <div class="invalid-feedback">Please provide your last name.</div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control form-control-lg" required>
                        <div class="invalid-feedback">Please enter a valid email address.</div>
                    </div>

                    <!-- Mobile Number -->
                    <div class="mb-3">
                        <label for="mobile" class="form-label">Mobile Number <span class="text-danger">*</span></label>
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
                        <label for="whatsappNumber" class="form-label">WhatsApp Number <span class="text-danger">*</span></label>
                        <input type="tel" id="whatsappNumber" name="whatsappNumber" class="form-control form-control-lg">
                        <div class="invalid-feedback">Please provide a WhatsApp number.</div>
                    </div>


                    <!-- Telegram Username -->
                    <div class="mb-3" id="telegramContainer" style="display: none;">
                        <label for="telegramUsername" class="form-label">Telegram Username <span class="text-danger">*</span></label>
                        <input type="text" id="telegramUsername" name="telegramUsername" class="form-control">
                    </div>

                    <!-- password -->

                    <div class="mb-3">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control form-control-lg" aria-autocomplete="list" required>
                            <button type="button" class="btn btn-light border" id="suggestPassword">Suggest</button>
                            <button type="button" class="btn btn-light border" id="togglePassword">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div class="password-strength mt-2">
                            <div class="progress" style="height: 5px;">
                                <div id="password-strength-bar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                            </div>
                            <small id="password-strength-text" class="form-text text-muted"></small>
                        </div>
                        <div class="form-text">Use 8+ characters with numbers, symbols, and mixed cases</div>
                        <div class="invalid-feedback">Please provide a password.</div>
                        <div class="valid-feedback">Looks good!</div>
                    </div>

                    <div class="mb-4">
                        <label for="repeatPassword" class="form-label">Repeat Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="repeatPassword" id="repeatPassword" class="form-control form-control-lg" required>
                            <button type="button" class="btn btn-light border" id="toggleRepeatPassword">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback" id="passwordMismatchFeedback" style="display: none;">
                            Passwords do not match.
                        </div>
                        <div class="valid-feedback" id="passwordMatchFeedback" style="display: none;">
                            Passwords match!
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const passwordField = document.getElementById('password');
                            const repeatPasswordField = document.getElementById('repeatPassword');
                            const suggestBtn = document.getElementById('suggestPassword');
                            const toggleBtn = document.getElementById('togglePassword');
                            const toggleRepeatBtn = document.getElementById('toggleRepeatPassword');
                            const mismatchFeedback = document.getElementById('passwordMismatchFeedback');
                            const matchFeedback = document.getElementById('passwordMatchFeedback');
                            const strengthBar = document.getElementById('password-strength-bar');
                            const strengthText = document.getElementById('password-strength-text');

                            // Password strength levels
                            const strengthLevels = [{
                                    text: "Very Weak",
                                    class: "bg-danger",
                                    min: 0
                                },
                                {
                                    text: "Weak",
                                    class: "bg-warning",
                                    min: 20
                                },
                                {
                                    text: "Moderate",
                                    class: "bg-info",
                                    min: 40
                                },
                                {
                                    text: "Strong",
                                    class: "bg-success",
                                    min: 60
                                },
                                {
                                    text: "Very Strong",
                                    class: "bg-primary",
                                    min: 80
                                }
                            ];

                            // Generate a random password
                            function generatePassword() {
                                const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()';
                                let password = '';
                                for (let i = 0; i < 12; i++) {
                                    password += chars.charAt(Math.floor(Math.random() * chars.length));
                                }
                                return password;
                            }

                            // Check password strength
                            function checkPasswordStrength(password) {
                                let score = 0;

                                // Length (up to 30 points)
                                score += Math.min(30, password.length * 3);

                                // Character variety
                                if (/[A-Z]/.test(password)) score += 10; // Uppercase
                                if (/[a-z]/.test(password)) score += 10; // Lowercase
                                if (/\d/.test(password)) score += 10; // Numbers
                                if (/[^A-Za-z0-9]/.test(password)) score += 10; // Symbols

                                // Deductions for common patterns
                                if (/password|1234|qwerty/i.test(password)) score = Math.max(0, score - 30);
                                if (/(.)\1{2,}/.test(password)) score = Math.max(0, score - 15); // Repeated chars

                                // Cap at 100
                                score = Math.min(100, score);

                                return score;
                            }

                            // Update strength meter
                            function updateStrengthMeter(password) {
                                const score = checkPasswordStrength(password);
                                const level = strengthLevels.slice().reverse().find(l => score >= l.min);

                                strengthBar.style.width = `${score}%`;
                                strengthBar.className = `progress-bar ${level.class}`;
                                strengthText.textContent = `${level.text} (${score}/100)`;
                                strengthText.className = `form-text ${level.class.replace('bg', 'text')}`;
                            }

                            // Toggle password visibility
                            function togglePasswordVisibility(field, btn) {
                                const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
                                field.setAttribute('type', type);
                                btn.querySelector('i').classList.toggle('bi-eye');
                                btn.querySelector('i').classList.toggle('bi-eye-slash');
                            }

                            // Check if passwords match
                            function checkPasswordMatch() {
                                if (passwordField.value && repeatPasswordField.value) {
                                    if (passwordField.value !== repeatPasswordField.value) {
                                        mismatchFeedback.style.display = 'block';
                                        matchFeedback.style.display = 'none';
                                        repeatPasswordField.classList.add('is-invalid');
                                        repeatPasswordField.classList.remove('is-valid');
                                    } else {
                                        mismatchFeedback.style.display = 'none';
                                        matchFeedback.style.display = 'block';
                                        repeatPasswordField.classList.remove('is-invalid');
                                        repeatPasswordField.classList.add('is-valid');
                                    }
                                } else {
                                    mismatchFeedback.style.display = 'none';
                                    matchFeedback.style.display = 'none';
                                    repeatPasswordField.classList.remove('is-invalid');
                                    repeatPasswordField.classList.remove('is-valid');
                                }
                            }

                            // Suggest password
                            suggestBtn.addEventListener('click', function() {
                                const suggestedPassword = generatePassword();
                                passwordField.value = suggestedPassword;
                                passwordField.setAttribute('type', 'text');
                                toggleBtn.querySelector('i').classList.remove('bi-eye');
                                toggleBtn.querySelector('i').classList.add('bi-eye-slash');

                                // Auto-fill repeat password and show it
                                repeatPasswordField.value = suggestedPassword;
                                repeatPasswordField.setAttribute('type', 'text');
                                toggleRepeatBtn.querySelector('i').classList.remove('bi-eye');
                                toggleRepeatBtn.querySelector('i').classList.add('bi-eye-slash');

                                // Update strength meter and validation
                                updateStrengthMeter(suggestedPassword);
                                checkPasswordMatch();
                                passwordField.focus();
                            });

                            // Toggle visibility for main password
                            toggleBtn.addEventListener('click', function() {
                                togglePasswordVisibility(passwordField, toggleBtn);
                            });

                            // Toggle visibility for repeat password
                            toggleRepeatBtn.addEventListener('click', function() {
                                togglePasswordVisibility(repeatPasswordField, toggleRepeatBtn);
                            });

                            // Check password match on input
                            passwordField.addEventListener('input', function() {
                                updateStrengthMeter(this.value);
                                checkPasswordMatch();
                            });

                            repeatPasswordField.addEventListener('input', checkPasswordMatch);
                        });
                    </script>
                    <!-- ./password -->

                    <div class="mb-3">
                        <label for="contactMethod" class="form-label">Choose Your Contact Preference <span class="text-danger">*</span></label>
                        <div id="contactMethodContainer">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="contactMethod[]" id="contactWhatsApp" value="whatsapp">
                                <label class="form-check-label" for="contactWhatsApp">WhatsApp</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="contactMethod[]" id="contactEmail" value="email">
                                <label class="form-check-label" for="contactEmail">Email</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="contactMethod[]" id="contactTelegram" value="telegram" onchange="toggleTelegramField()">
                                <label class="form-check-label" for="contactTelegram">Telegram</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="contactMethod[]" id="contactCall" value="call">
                                <label class="form-check-label" for="contactCall">Call</label>
                            </div>
                        </div>
                        <div class="invalid-feedback" id="contactMethodFeedback" style="display: none;">Please select at least one contact method.</div>
                    </div>


                    <div class="mb-3 form-check terms-checkbox">
                        <input type="checkbox" class="form-check-input" id="tos" required>
                        <label class="form-check-label" for="tos">I accept the <a href="#" data-bs-toggle="modal" data-bs-target="#tosModal">Terms of Service</a> <span class="text-danger">*</span></label>
                        <div class="invalid-feedback">You must accept the Terms of Service.</div>
                    </div>

                    <div class="mb-3 form-check terms-checkbox">
                        <input type="checkbox" class="form-check-input" id="privacy" required>
                        <label class="form-check-label" for="privacy">I accept the <a href="#" data-bs-toggle="modal" data-bs-target="#privacyModal">Privacy Policy</a> <span class="text-danger">*</span></label>
                        <div class="invalid-feedback">You must accept the Privacy Policy.</div>
                    </div>
                    <input type="hidden" name="country_code" id="country_code">
                    <input type="hidden" name="country_code_alpha" id="country_code_alpha">
                    <input type="hidden" name="country_name" id="country_name">

                    <!-- captcha -->
                    <div class="card mb-2">
                        <div class="card-header">
                            <h5 class="card-text">Verify You're Human <span class="text-danger">*</span></h5>
                        </div>
                        <div class="card-body">
                            <div class="checkbox-captcha-container text-center">
                                <!-- Hidden honeypot field -->
                                <input type="text" name="website" id="website" class="d-none" autocomplete="off">

                                <div class="form-check justify-content-center">
                                    <input class="form-check-input" type="checkbox" id="human-verification" disabled>
                                    <label class="form-check-label" for="human-verification">
                                        I am not a robot
                                    </label>
                                </div>
                                <div id="captcha-status" class="text-danger mt-2"></div>
                                <div id="checkbox-timer" class="text-muted small mt-2"></div>
                            </div>
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
    customScripts: [
        'https://cdn.jsdelivr.net/npm/libphonenumber-js@1.12.6/bundle/libphonenumber-js.min.js'
    ],
    includeSwal: false,
    includeNotiflix: true
);
?>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.0/build/js/intlTelInput.min.js"></script>

<!-- Add Firebase OTP Verification Modal -->
<div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="otpModalLabel">Verify Your Phone Number</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="otp" class="form-label">Enter OTP</label>
                    <input type="text" class="form-control" id="otp" placeholder="Enter 6-digit OTP" maxlength="6">
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-link" id="resendOtp">Resend OTP</button>
                    <span id="otpTimer" class="text-muted"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="verifyOtp">Verify</button>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/intl.js"></script>
<script>
    const hu = '<?= $hu; ?>';

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
    const signupButton = document.getElementById("submit-btn");

    document.addEventListener("DOMContentLoaded", () => {
        console.log('DOM Content Loaded'); // Debug log

        // Initial setup
        whatsappRadioContainer.style.display = 'none';
        whatsappContainer.style.display = 'none';
        telegramContainer.style.display = 'none';

        handleWhatsAppSelection();
        handleWhatsAppNumberVisibility();
        toggleTelegramField();

        // Event listeners
        contactWhatsApp.addEventListener('change', handleWhatsAppSelection);
        whatsappYes.addEventListener('change', handleWhatsAppNumberVisibility);
        whatsappNo.addEventListener('change', handleWhatsAppNumberVisibility);
        contactTelegram.addEventListener('change', toggleTelegramField);

        form.addEventListener("submit", async (e) => {
            console.log('Form submit event triggered'); // Debug log
            e.preventDefault();
            form.classList.add("was-validated");

            // Check verification
            if (!document.getElementById('human-verification').checked) {
                console.log('Human verification failed'); // Debug log
                Notiflix.Notify.failure("Please verify you are human.");
                return;
            }

            // Disable signup button to prevent multiple requests
            signupButton.disabled = true;
            signupButton.innerText = "Signing Up...";

            // Password validation
            if (form.password.value !== form.repeatPassword.value) {
                console.log('Password mismatch'); // Debug log
                document.getElementById("passwordMismatchFeedback").style.display = 'block';
                signupButton.disabled = false;
                signupButton.innerText = "Sign Up";
                return;
            }

            // WhatsApp validation
            if (contactWhatsApp.checked && whatsappNo.checked && !whatsappNumber.value) {
                console.log('WhatsApp validation failed'); // Debug log
                whatsappNumber.classList.add('is-invalid');
                Notiflix.Notify.failure("Please provide a WhatsApp number.");
                signupButton.disabled = false;
                signupButton.innerText = "Sign Up";
                return;
            } else {
                whatsappNumber.classList.remove('is-invalid');
            }

            // Telegram validation
            if (contactTelegram.checked && !telegramUsername.value) {
                console.log('Telegram validation failed'); // Debug log
                telegramUsername.classList.add('is-invalid');
                Notiflix.Notify.failure("Please provide a Telegram username.");
                signupButton.disabled = false;
                signupButton.innerText = "Sign Up";
                return;
            } else {
                telegramUsername.classList.remove('is-invalid');
            }

            // Mobile Number Validation
            if (!mobileNo.value) {
                console.log('Mobile number validation failed'); // Debug log
                mobileNo.classList.add('is-invalid');
                Notiflix.Notify.failure("Please enter a mobile number.");
                signupButton.disabled = false;
                signupButton.innerText = "Sign Up";
                return;
            }

            // Get the international telephone input instance
            const iti = window.intlTelInputGlobals.getInstance(mobileNo);
            if (!iti) {
                console.log('Country code selection failed'); // Debug log
                mobileNo.classList.add('is-invalid');
                Notiflix.Notify.failure("Please select a country code.");
                signupButton.disabled = false;
                signupButton.innerText = "Sign Up";
                return;
            }

            const isValidNumber = iti.isValidNumber();

            if (!isValidNumber) {
                console.log('Invalid phone number format'); // Debug log
                mobileNo.classList.add('is-invalid');
                Notiflix.Notify.failure("Please enter a valid mobile number with country code.");
                signupButton.disabled = false;
                signupButton.innerText = "Sign Up";
                return;
            } else {
                mobileNo.classList.remove('is-invalid');
                // Update hidden fields with country data
                const countryData = iti.getSelectedCountryData();
                document.getElementById('country_code').value = countryData.dialCode;
                document.getElementById('country_code_alpha').value = countryData.iso2;
                document.getElementById('country_name').value = countryData.name;
            }

            // Submit form
            try {
                console.log('Starting form submission'); // Debug log
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
                    console.log('No contact method selected'); // Debug log
                    Notiflix.Notify.failure("Please select at least one contact method.");
                    signupButton.disabled = false;
                    signupButton.innerText = "Sign Up";
                    return;
                }

                jsonData.contactMethod = selectedMethods;

                console.log('Form data prepared:', jsonData); // Debug log
                console.log('API endpoint:', 'api/v1/signup'); // Debug log
                console.log('HU value:', hu); // Debug log

                const res = await fetch("api/v1/signup", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "HU": hu
                    },
                    body: JSON.stringify({
                        ...jsonData,
                        contactMethod: selectedMethods // Include the converted array
                    })
                });

                console.log('Response received, status:', res.status); // Debug log
                const data = await res.json();
                console.log('Response data:', data); // Debug log

                if (data.success) {
                    console.log('Signup successful'); // Debug log
                    Notiflix.Notify.success(data.success);
                    form.reset();
                    form.classList.remove("was-validated");
                    handleWhatsAppSelection();
                    toggleTelegramField();

                    // Move to login page if sign up completed
                    const params = new URLSearchParams(window.location.search);
                    const o = params.get("o");
                    location.href = o ? `auth/login?o=${encodeURIComponent(o)}` : "auth/login?through=signup";
                } else {
                    console.log('Signup failed:', data.error); // Debug log
                    Notiflix.Notify.failure(data.error || "Signup failed. Please try again.");
                }
            } catch (err) {
                console.error('Form submission error:', err); // Debug log
                console.error('Error stack:', err.stack); // Debug log
                Notiflix.Notify.failure('An error occurred. Please try again. ' + err);
            } finally {
                signupButton.disabled = false;
                signupButton.innerText = "Sign Up";
            }
        });
    });

    // Anti-bot verification setup
    document.addEventListener('DOMContentLoaded', function() {
        const verificationCheckbox = document.getElementById('human-verification');
        const captchaStatus = document.getElementById('captcha-status');
        const checkboxTimer = document.getElementById('checkbox-timer');
        const signupButton = document.getElementById('submit-btn');
        const form = document.getElementById('regForm');
        const honeypot = document.getElementById('website');

        // Track user behavior
        let mouseMoved = false;
        let mouseMoveCount = 0;
        let keyPressCount = 0;
        let fieldFocusCount = 0;
        let startTime = Date.now();
        let formFillTime = 0;
        let checkboxEnabled = false;
        let checkboxEnableTime = 0;
        let lastSubmissionTime = 0;
        let submissionCount = 0;

        // Generate a simple browser fingerprint
        function getBrowserFingerprint() {
            return {
                userAgent: navigator.userAgent,
                language: navigator.language,
                platform: navigator.platform,
                screenResolution: `${window.screen.width}x${window.screen.height}`,
                colorDepth: window.screen.colorDepth,
                timezone: new Date().getTimezoneOffset(),
                hasLocalStorage: !!window.localStorage,
                hasSessionStorage: !!window.sessionStorage,
                hasCookies: navigator.cookieEnabled
            };
        }

        // Initialize signup button as disabled
        signupButton.disabled = true;
        signupButton.classList.remove('btn-primary');
        signupButton.classList.add('btn-secondary');

        // Track mouse movement
        document.addEventListener('mousemove', function() {
            mouseMoved = true;
            mouseMoveCount++;
        });

        // Track keyboard interaction
        document.addEventListener('keydown', function() {
            keyPressCount++;
        });

        // Track form field focus
        form.addEventListener('focusin', function() {
            fieldFocusCount++;
        });

        // Track form fill time
        form.addEventListener('input', function() {
            formFillTime = Date.now() - startTime;
        });

        // Enable checkbox after delay
        function enableCheckbox() {
            if (!checkboxEnabled) {
                // Enable if any of these conditions are met:
                // 1. Form fill time > 2 seconds
                // 2. User has interacted with keyboard
                // 3. User has focused on fields
                if (formFillTime > 2000 || keyPressCount > 0 || fieldFocusCount > 0) {
                    verificationCheckbox.disabled = false;
                    checkboxEnabled = true;
                    checkboxEnableTime = Date.now();
                    checkboxTimer.textContent = 'Checkbox enabled';
                }
            }
        }

        // Update checkbox timer
        function updateCheckboxTimer() {
            if (!checkboxEnabled) {
                // Show time remaining based on form fill time
                const timeLeft = Math.max(0, Math.ceil((2000 - formFillTime) / 1000));
                if (timeLeft > 0) {
                    checkboxTimer.textContent = `Checkbox will be enabled in ${timeLeft} seconds`;
                } else {
                    checkboxTimer.textContent = 'Checkbox will be enabled after interaction';
                }
            }
        }

        // Track form fill time more frequently
        setInterval(function() {
            formFillTime = Date.now() - startTime;
        }, 100);

        // Periodically check if checkbox should be enabled
        setInterval(enableCheckbox, 100);
        setInterval(updateCheckboxTimer, 100);

        // Add event listener for checkbox
        verificationCheckbox.addEventListener('change', function() {
            if (this.checked) {
                // Check if user has interacted naturally
                if (!mouseMoved || mouseMoveCount < 3 || keyPressCount < 2 || fieldFocusCount < 2 || formFillTime < 2000) {
                    captchaStatus.textContent = 'Please interact with the form naturally';
                    captchaStatus.className = 'text-danger mt-2';
                    this.checked = false;
                    return;
                }

                // Check if checkbox was enabled for at least 2 seconds
                if (Date.now() - checkboxEnableTime < 2000) {
                    captchaStatus.textContent = 'Please wait a moment before checking';
                    captchaStatus.className = 'text-danger mt-2';
                    this.checked = false;
                    return;
                }

                captchaStatus.textContent = 'Verification successful!';
                captchaStatus.className = 'text-success mt-2';
                signupButton.disabled = false;
                signupButton.classList.remove('btn-secondary');
                signupButton.classList.add('btn-primary');
            } else {
                captchaStatus.textContent = 'Please verify you are human';
                captchaStatus.className = 'text-danger mt-2';
                signupButton.disabled = true;
                signupButton.classList.remove('btn-primary');
                signupButton.classList.add('btn-secondary');
            }
        });

        // Update form submission to check verification
        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            form.classList.add("was-validated");

            // Check submission rate
            if (!checkSubmissionRate()) {
                return;
            }

            // Check honeypot field
            if (honeypot.value) {
                console.log('Bot detected: Honeypot field filled');
                return;
            }

            // Check verification
            if (!verificationCheckbox.checked) {
                Notiflix.Notify.failure("Please verify you are human.");
                return;
            }

            // Check if form was filled too quickly
            if (formFillTime < 2000) {
                Notiflix.Notify.failure("Please fill out the form naturally.");
                return;
            }

            // Check if user has interacted naturally
            if (!mouseMoved || mouseMoveCount < 3 || keyPressCount < 2 || fieldFocusCount < 2) {
                Notiflix.Notify.failure("Please interact with the form naturally.");
                return;
            }

            // Add browser fingerprint to form data
            const formData = new FormData(form);
            formData.append('browserFingerprint', JSON.stringify(getBrowserFingerprint()));

            // Rest of your existing form submission code...
            submitForm();
        });
    });
</script>



</body>

</html>