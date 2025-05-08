<?php

defined('BASE_DIR') || die('Direct access denied');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Redirect to home if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: ../home');
    exit;
}

// Include required files
require 'inc/html_head.php';
require 'inc/html_foot.php';
require 'database.php';
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

// Write schema for this web page    
$webPageSchema = new Schema(
    new Thing('WebPage', [
        'url' => 'https://fayyaztravels.com/visa/auth/login',
        'name' => 'Login',
        'description' => 'Log in to access your visa applications.',
        'author' => new Thing('Organization', [
            'name' => 'Fayyaz Travels',
            'url' => 'https://fayyaztravels.com/visa'
        ])
    ])
);

// Schema for breadcrumb
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
                'name' => 'Login',
                'url' => 'https://fayyaztravels.com/visa/auth/login'
            ])
        ]
    ])
);

$metatags = new MetaTags();

$metatags
    ->description('Log in to access your visa applications.')
    ->meta('author', 'Fayyaz Travels')
    ->meta('robots', 'index, follow')
    ->image('https://fayyaztravels.com/og/index.php?title=' . urlencode('Login') . '&subtitle=' . urlencode('Log in to access your visa applications.') . '&gradient=' . urlencode('sunset_meadow'))
    ->canonical('https://fayyaztravels.com/visa/auth/login');

// Convert both to strings and concatenate
$seo = $webPageSchema . "\n" . $breadcrumbSchema . "\n" . $schema . "\n" . $metatags;
// SEO END

// Output HTML head and scripts
echo html_head('Log in', null, true, ['assets/css/signup.css'], true);
?>

<div class="login-container">
    <div class="login-wrapper">
        <!-- Left Panel -->
        <div class="login-panel info-panel">
            <div class="brand-container d-flex align-items-center justify-content-between">
                <a href="" class="text-white text-decoration-none fs-5 fw-bold"><i class="bi bi-house"></i> Back to Home</a>
                <img src="assets/images/main-logo-white.png" alt="Fayyaz Travels" class="brand-logo" width="150px">
            </div>
            <div class="info-content">
                <h1>Explore the World</h1>
                <p>Your journey begins with a simple login. Access your visa applications and travel with confidence.</p>

                <div class="features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="bi bi-clock-fill"></i>
                        </div>
                        <div class="feature-text">
                            <h3>Fast Processing</h3>
                            <p>Get your visa processed quickly and efficiently</p>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <div class="feature-text">
                            <h3>Real-time Tracking</h3>
                            <p>Monitor your application status anytime, anywhere</p>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="bi bi-headset"></i>
                        </div>
                        <div class="feature-text">
                            <h3>24/7 Support</h3>
                            <p>Expert assistance available whenever you need it</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="decoration-element"></div>
            <div class="decoration-circle circle-1"></div>
            <div class="decoration-circle circle-2"></div>
        </div>

        <!-- Right Panel -->
        <div class="login-panel form-panel">
            <div class="form-container">
                <?php if (isset($_GET['goto']) || isset($_GET['through']) || isset($_GET['password_changed'])): ?>
                    <div class="notification-area">
                        <?php if (isset($_GET['goto'])): ?>
                            <div class="alert-message warning">
                                <i class="bi bi-exclamation-circle"></i>
                                <span>Please log in first to continue.</span>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($_GET['through']) && $_GET['through'] === 'new_password'): ?>
                            <div class="alert-message warning">
                                <i class="bi bi-exclamation-circle"></i>
                                <span>Welcome! Please log in with your credentials.</span>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($_GET['through']) && $_GET['through'] === 'signup'): ?>
                            <div class="alert-message success">
                                <i class="bi bi-check-circle"></i>
                                <span>Account created successfully! Please check your inbox or spam folder to verify your account.</span>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($_GET['through']) && $_GET['through'] === 'email_verification'): ?>
                            <div class="alert-message success">
                                <i class="bi bi-check-circle"></i>
                                <span>Your account has been verified successfully. Please log in.</span>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($_GET['through']) && $_GET['through'] === 'new_signup'): ?>
                            <div class="alert-message success">
                                <i class="bi bi-person-check"></i>
                                <span>Congratulations! Your account has been created and verified successfully.</span>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($_GET['password_changed']) && $_GET['password_changed'] === '1'): ?>
                            <div class="alert-message success">
                                <i class="bi bi-shield-check"></i>
                                <span>Your password has been changed successfully. Please log in with your new password.</span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="form-header">
                    <h2>Welcome Back</h2>
                    <p>Sign in to continue your visa journey</p>
                </div>

                <form novalidate autocomplete="off" id="loginForm" class="login-form">
                    <div class="form-group">
                        <div class="input-with-icon">
                            <i class="bi bi-envelope"></i>
                            <input type="email" name="email" id="email" placeholder="Email address" required>
                        </div>
                        <div class="invalid-feedback">Please enter a valid email address.</div>
                    </div>

                    <div class="form-group">
                        <div class="input-with-icon">
                            <i class="bi bi-lock"></i>
                            <input type="password" name="password" id="password" placeholder="Password" required>
                            <button type="button" class="toggle-password" aria-label="Toggle password visibility">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback">Please provide your password.</div>
                    </div>

                    <div class="form-options">
                        <a href="auth/forgot-password<?= isset($_GET['o']) ? '?o=' . htmlspecialchars($_GET['o']) : ''; ?>" class="forgot-link">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn-submit">
                        <span>Sign In</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>

                    <div class="divider"></div>

                    <div class="social-login">
                        <button type="button" class="social-btn google" disabled>
                            <i class="bi bi-google"></i>
                            <span>Google</span>
                        </button>
                        <button type="button" class="social-btn apple" disabled>
                            <i class="bi bi-apple"></i>
                            <span>Apple</span>
                        </button>
                    </div>

                    <div class="signup-link">
                        <p>Don't have an account? <a href="auth/signup<?= isset($_GET['o']) ? '?o=' . trim(strip_tags(stripslashes($_GET['o']))) : ''; ?>">Create Account</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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
    :root {
        --primary: #14385C;
        --primary-light: #3a6e8f;
        --primary-dark: #0c2c44;
        --secondary: #af8700;
        --secondary-light: #ffc300;
        --secondary-dark: #876900;
        --text-dark: #333333;
        --text-light: #666666;
        --text-lighter: #999999;
        --white: #ffffff;
        --off-white: #f8f9fa;
        --success: #28a745;
        --warning: #ffc107;
        --danger: #dc3545;
        --border-radius: 12px;
        --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background-color: var(--off-white);
        color: var(--text-dark);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-container {
        width: 100%;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
    }

    .login-wrapper {
        display: flex;
        width: 100%;
        max-width: 1200px;
        min-height: 600px;
        background-color: var(--white);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    }

    .login-panel {
        flex: 1;
        padding: 40px;
        position: relative;
    }

    /* Info Panel (Left) */
    .info-panel {
        background: linear-gradient(135deg, #1a4f80, #0c2c44);
        color: var(--white);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        padding: 60px 40px;
    }

    .brand-container {
        margin-bottom: 40px;
        position: relative;
        z-index: 5;
    }

    .brand-logo {
        height: 50px;
        object-fit: contain;
        filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
    }

    .info-content {
        z-index: 5;
        max-width: 90%;
    }

    .info-content h1 {
        font-size: 2.8rem;
        font-weight: 800;
        margin-bottom: 20px;
        background: linear-gradient(to right, var(--white), var(--secondary-light));
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: -0.5px;
    }

    .info-content p {
        font-size: 1.1rem;
        margin-bottom: 40px;
        opacity: 0.9;
        line-height: 1.7;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .features {
        margin-top: 50px;
        position: relative;
        z-index: 5;
    }

    .feature-item {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        background: rgba(255, 255, 255, 0.05);
        padding: 15px;
        border-radius: 12px;
        backdrop-filter: blur(5px);
        transition: transform 0.3s ease;
    }

    .feature-item:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.1);
    }

    .feature-icon {
        width: 55px;
        height: 55px;
        background: linear-gradient(135deg, var(--secondary), var(--secondary-light));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        box-shadow: 0 5px 15px rgba(175, 135, 0, 0.3);
    }

    .feature-icon i {
        font-size: 1.5rem;
        color: var(--white);
    }

    .feature-text h3 {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .feature-text p {
        font-size: 0.9rem;
        opacity: 0.8;
        margin: 0;
    }

    .decoration-element {
        position: absolute;
        bottom: -100px;
        right: -100px;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, var(--secondary) 0%, transparent 70%);
        opacity: 0.2;
        border-radius: 50%;
        z-index: 1;
    }

    .decoration-circle {
        position: absolute;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
        z-index: 1;
    }

    .circle-1 {
        width: 200px;
        height: 200px;
        top: -50px;
        left: -50px;
    }

    .circle-2 {
        width: 300px;
        height: 300px;
        bottom: 10%;
        right: -150px;
        opacity: 0.3;
    }

    /* Form Panel (Right) */
    .form-panel {
        background-color: var(--white);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 60px 40px;
    }

    .form-container {
        width: 100%;
        max-width: 400px;
    }

    .notification-area {
        margin-bottom: 30px;
    }

    .alert-message {
        display: flex;
        align-items: center;
        padding: 16px;
        border-radius: 12px;
        margin-bottom: 15px;
        font-size: 0.95rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .alert-message i {
        margin-right: 12px;
        font-size: 1.3rem;
    }

    .alert-message.success {
        background-color: rgba(40, 167, 69, 0.1);
        color: var(--success);
        border-left: 4px solid var(--success);
    }

    .alert-message.warning {
        background-color: rgba(255, 193, 7, 0.1);
        color: var(--warning);
        border-left: 4px solid var(--warning);
    }

    .form-header {
        text-align: center;
        margin-bottom: 35px;
    }

    .form-header h2 {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 10px;
        letter-spacing: -0.5px;
    }

    .form-header p {
        color: var(--text-light);
        font-size: 1.05rem;
    }

    .login-form .form-group {
        margin-bottom: 24px;
    }

    .input-with-icon {
        position: relative;
    }

    .input-with-icon i {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-lighter);
        font-size: 1.1rem;
    }

    .login-form input {
        width: 100%;
        padding: 16px 16px 16px 50px;
        border: 2px solid rgba(0, 0, 0, 0.08);
        border-radius: 14px;
        font-size: 1rem;
        transition: var(--transition);
        background-color: var(--white);
    }

    .login-form input:focus {
        outline: none;
        border-color: var(--secondary);
        box-shadow: 0 0 0 4px rgba(175, 135, 0, 0.1);
    }

    .toggle-password {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--text-lighter);
        cursor: pointer;
        font-size: 1.1rem;
    }

    .form-options {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 30px;
    }

    .forgot-link {
        color: var(--primary);
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 500;
        transition: var(--transition);
    }

    .forgot-link:hover {
        color: var(--secondary);
        text-decoration: underline;
    }

    .btn-submit {
        width: 100%;
        padding: 16px;
        background: linear-gradient(to right, var(--secondary), var(--secondary-light));
        border: none;
        border-radius: 14px;
        color: var(--white);
        font-size: 1.05rem;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
        box-shadow: 0 8px 20px rgba(175, 135, 0, 0.25);
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 25px rgba(175, 135, 0, 0.35);
    }

    .btn-submit i {
        margin-left: 10px;
        transition: var(--transition);
    }

    .btn-submit:hover i {
        transform: translateX(5px);
    }

    .divider {
        height: 1px;
        background: linear-gradient(to right, rgba(0, 0, 0, 0.03), rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.03));
        margin: 35px 0;
        position: relative;
    }

    .social-login {
        display: flex;
        gap: 15px;
        margin-bottom: 35px;
    }

    .social-btn {
        flex: 1;
        padding: 14px;
        border-radius: 14px;
        border: 2px solid rgba(0, 0, 0, 0.08);
        background-color: var(--white);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-weight: 500;
        color: var(--text-dark);
        transition: var(--transition);
        cursor: pointer;
    }

    .social-btn:not(:disabled):hover {
        background-color: var(--off-white);
        transform: translateY(-2px);
    }

    .social-btn i {
        font-size: 1.3rem;
    }

    .social-btn.google i {
        color: #DB4437;
    }

    .social-btn.apple i {
        color: #000000;
    }

    .signup-link {
        text-align: center;
        margin-top: 25px;
    }

    .signup-link p {
        color: var(--text-light);
        font-size: 1rem;
    }

    .signup-link a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
        transition: var(--transition);
    }

    .signup-link a:hover {
        color: var(--secondary);
        text-decoration: underline;
    }

    .invalid-feedback {
        color: var(--danger);
        font-size: 0.85rem;
        margin-top: 8px;
        display: none;
    }

    .was-validated .form-group input:invalid~.invalid-feedback {
        display: block;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .login-wrapper {
            flex-direction: column;
            max-width: 550px;
        }

        .info-panel {
            padding: 40px 30px;
        }

        .info-content h1 {
            font-size: 2.2rem;
        }

        .info-content p {
            font-size: 1rem;
            margin-bottom: 20px;
        }

        .features {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 30px;
        }

        .feature-item {
            flex: 1 1 calc(50% - 15px);
            margin-bottom: 0;
            min-width: 200px;
        }
    }

    @media (max-width: 768px) {
        .features {
            display: none;
        }
    }

    @media (max-width: 576px) {
        .login-panel {
            padding: 30px 25px;
        }

        .form-header h2 {
            font-size: 1.8rem;
        }

        .social-login {
            flex-direction: column;
        }

        .login-form input {
            padding: 14px 14px 14px 45px;
        }

        .btn-submit {
            padding: 14px;
        }
    }
</style>

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", () => {
        const form = document.getElementById("loginForm");
        const togglePasswordBtn = document.querySelector(".toggle-password");
        const passwordInput = document.getElementById("password");

        // Toggle password visibility
        if (togglePasswordBtn) {
            togglePasswordBtn.addEventListener("click", () => {
                const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
                passwordInput.setAttribute("type", type);
                togglePasswordBtn.innerHTML = type === "password" ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
            });
        }

        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            form.classList.add("was-validated");

            if (!form.checkValidity()) return;

            const jsonData = Object.fromEntries(new FormData(form));

            try {
                const res = await fetch("api/v1/login", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "HU": "<?= isset($hu) ? $hu : ''; ?>"
                    },
                    body: JSON.stringify(jsonData),
                });

                const {
                    success,
                    error
                } = await res.json();

                if (success) {
                    Notiflix.Notify.success(success);
                    form.reset();
                    form.classList.remove("was-validated");

                    const params = new URLSearchParams(window.location.search);
                    const goto = params.get("goto");
                    const o = params.get("o");

                    location.href = goto ?
                        `application/${goto}/persona` :
                        o ?
                        `application/${o}/persona?through=login` :
                        "home";

                } else {
                    Notiflix.Notify.failure(error || "Login failed. Please try again.");
                }
            } catch (err) {
                Notiflix.Notify.failure("An error occurred. Please try again.");
            }
        });
    });
</script>