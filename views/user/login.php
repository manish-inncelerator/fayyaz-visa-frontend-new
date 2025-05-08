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

// write schema for this web page    
$webPageSchema = new Schema(
    new Thing('WebPage', [
        'url' => 'https://fayyaztravels.com/visa/auth/login',
        'name' => 'Reset Password',
        'description' => 'Reset your password.',
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
                'name' => 'Reset Password',
                'url' => 'https://fayyaztravels.com/visa/auth/login'
            ])
        ]
    ])
);






$metatags = new MetaTags();

$metatags
    ->description('Reset your password.')
    ->meta('author', 'Fayyaz Travels')
    ->meta('robots', 'index, follow')
    ->image($url . '/og/index.php?title=' . urlencode('Reset Password') . '&subtitle=' . urlencode('Reset your password.') . '&gradient=' . urlencode('sunset_meadow'))
    ->canonical('https://fayyaztravels.com/visa/auth/login');

// Convert both to strings and concatenate
$seo = $webPageSchema . "\n" . $breadcrumbSchema . "\n" . $schema . "\n" . $metatags;
// SEO END

// Output HTML head and scripts
echo html_head('Log in', null, true, ['assets/css/signup.css'], true);
?>
<!-- Navbar -->
<?php require 'components/SimpleNavbar.php'; ?>
<!-- ./Navbar -->

<!-- Login Section -->
<section class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="row w-100 justify-content-center">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="form-container rounded-3 bg-white">
                <?php if (isset($_GET['goto'])): ?>
                    <p class="alert alert-warning text-center mb-4"><i class="bi bi-exclamation-circle"></i> Please log in first.</p>
                <?php endif; ?>
                <?php if (isset($_GET['through']) && $_GET['through'] === 'new_password'): ?>
                    <p class="alert alert-warning text-center mb-4"><i class="bi bi-exclamation-circle"></i> Welcome! Please log in.</p>
                <?php endif; ?>
                <?php if (isset($_GET['through']) && $_GET['through'] === 'email_verification'): ?>
                    <p class="alert alert-success text-center mb-4"><i class="bi bi-info-circle"></i> Your account has been verified successfully. Please log in. </p>
                <?php endif; ?>
                <?php if (isset($_GET['through']) && $_GET['through'] === 'signup'): ?>
                    <p class="alert alert-success text-center mb-4"><i class="bi bi-person-circle"></i> Congratulations! Your account has been created and verified successfully. Please verify your email to continue.</p>
                    <p class="alert alert-info text-center card-text"><i class="bi bi-info-circle"></i> Please check for verification email in your inbox or spam folder.</p>
                    <div class="text-center card-text">
                        <a href="/visa/home" class="btn btn-blue rounded-pill mt-2 btn-lg"><i class="bi bi-arrow-left-circle"></i> Back to Home</a>
                    </div>
                    <?php die(); ?>
                    <?php endif; ?><?php if (isset($_GET['password_changed']) && $_GET['password_changed'] === '1'): ?>
                    <p class="alert alert-success text-center mb-4">
                        <i class="bi bi-info-circle"></i> Your password has been changed successfully. Please log in with your new password.
                    </p>
                <?php endif; ?>

                <!-- Icon and Header -->
                <div class="text-center">
                    <div class="icon-circle mx-auto">
                        <i class="bi bi-person fs-4"></i>
                    </div>
                    <h4 class="text-brown mb-2">Welcome Back</h4>
                    <p class="text-muted mb-4">Log in to continue your visa journey.</p>
                </div>

                <!-- Login Form -->
                <form novalidate autocomplete="off" id="loginForm">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" name="email" id="email" class="form-control form-control-lg" required>
                        <div class="invalid-feedback">Please enter a valid email address.</div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <label for="password" class="form-label">Password</label>
                            <!-- <a href="auth/forgot-password">Forgot Password?</a> -->
                            <?php if (isset($_GET['o'])): ?>
                                <p class="card-text fw-bold"><a href="auth/forgot-password?o=<?= htmlspecialchars($_GET['o']); ?>">Forgot Password?</a></p>
                            <?php else: ?>
                                <p class="card-text fw-bold"><a href="auth/forgot-password">Forgot Password?</a></p>
                            <?php endif; ?>
                        </div>
                        <input type="password" name="password" id="password" class="form-control form-control-lg" required>
                        <div class="invalid-feedback">Please provide your password.</div>
                    </div>

                    <button type="submit" class="btn cta-button w-100 btn-lg rounded-pill p-2 plexFont fw-bold">Log In</button>
                </form>

                <div class="divider">
                    <span class="px-2 bg-white text-muted fw-bold">OR</span>
                </div>

                <p class="card-text fw-bold">New here?
                    <?php if (isset($_GET['o']) && !empty(trim(strip_tags(stripslashes($_GET['o']))))) : ?>
                        <a href="auth/signup?o=<?= trim(strip_tags(stripslashes($_GET['o']))) ?>">Sign up</a>
                    <?php else : ?>
                        <a href="auth/signup">Sign up</a>
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>
</section>

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

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", () => {
        const form = document.getElementById("loginForm");

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
                        "HU": "<?= $hu; ?>"
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
            } catch {
                Notiflix.Notify.failure("An error occurred. Please try again.");
            }
        });
    });
</script>

</body>

</html>