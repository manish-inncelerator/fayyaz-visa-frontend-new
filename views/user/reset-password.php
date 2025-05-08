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

// Output HTML head and scripts
if (isset($_GET['reset'])) {
    $title = "Reset Password";
} else {

    $title = "Forgot Password";
}


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
        'url' => 'https://fayyaztravels.com/visa/auth/reset-password',
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
                'url' => 'https://fayyaztravels.com/visa/auth/reset-password'
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
    ->canonical('https://fayyaztravels.com/visa/auth/reset-password');

// Convert both to strings and concatenate
$seo = $webPageSchema . "\n" . $breadcrumbSchema . "\n" . $schema . "\n" . $metatags;
// SEO END

echo html_head($title, null, true, ['assets/css/signup.css'], true, false, $seo);
?>
<!-- Navbar -->
<?php require 'components/SimpleNavbar.php'; ?>
<!-- ./Navbar -->

<!-- Login Section -->
<section class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="row w-100 justify-content-center">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="form-container rounded-3 bg-white">


                <!-- Icon and Header -->
                <div class="text-center">
                    <div class="icon-circle mx-auto">
                        <i class="bi bi-person fs-4"></i>
                    </div>
                    <?php if (isset($_GET['reset'])): ?>

                        <h4 class="text-brown mb-2">Set it right</h4>
                        <p class="text-muted mb-4">
                            Secure and tight!
                        </p>
                    <?php else: ?>




                        <h4 class="text-brown mb-2">Lost your key?</h4>
                        <p class="text-muted mb-4">Reset with ease, hassle-free!</p>





                    <?php endif; ?>
                </div>

                <!-- Login Form -->
                <?php if (isset($_GET['reset']) && $_GET['reset'] === '1' && isset($_GET['resetCode']) && !empty($_GET['resetCode'])): ?>
                    <form novalidate autocomplete="off" id="resetPasswordForm">
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New Password</label>
                            <input type="password" name="newPassword" id="newPassword" class="form-control form-control-lg" required>
                            <div class="invalid-feedback">Please enter a new password.</div>
                        </div>

                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm Password</label>
                            <input type="password" name="confirmPassword" id="confirmPassword" class="form-control form-control-lg" required>
                            <div class="invalid-feedback">Passwords must match.</div>
                        </div>
                        <input type="hidden" name="email" value="<?php echo isset($_GET['email']) ? trim(strip_tags(stripslashes($_GET['email']))) : ''; ?>">
                        <input type="hidden" name="resetCode" value="<?php echo isset($_GET['email']) ? trim(strip_tags(stripslashes($_GET['resetCode']))) : ''; ?>">
                        <button type="submit" class="btn cta-button w-100 btn-lg rounded-pill p-2 plexFont fw-bold">Set New Password</button>
                    </form>

                <?php else: ?>
                    <form novalidate autocomplete="off" id="resetFormAct">
                        <p id="alertInfo"></p>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" name="email" id="email" class="form-control form-control-lg" required>
                            <div class="invalid-feedback">Please enter a valid email address.</div>
                        </div>

                        <button type="submit" class="btn cta-button w-100 btn-lg rounded-pill p-2 plexFont fw-bold">Reset Password</button>
                    </form>
                    <div class="divider">
                        <span class="px-2 bg-white text-muted fw-bold">OR</span>
                    </div>

                    <p class="card-text fw-bold">Already registered? <a href="auth/login">Log in</a></p>
                <?php endif; ?>
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


<?php if (isset($_GET['reset']) && $_GET['reset'] === '1' && isset($_GET['resetCode']) && !empty($_GET['resetCode'])): ?>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", () => {
            const form = document.getElementById("resetPasswordForm");

            form.addEventListener("submit", async (e) => {
                e.preventDefault();
                form.classList.add("was-validated");

                if (!form.checkValidity()) return;

                const jsonData = Object.fromEntries(new FormData(form));

                try {
                    const res = await fetch("api/v1/change_password", {
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

                        location.href = 'auth/login?password_changed=1'

                    } else {
                        Notiflix.Notify.failure(error || "Login failed. Please try again.");
                    }
                } catch {
                    Notiflix.Notify.failure("An error occurred. Please try again.");
                }
            });
        });
    </script>
<?php else: ?>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", () => {
            const form = document.getElementById("resetFormAct");

            form.addEventListener("submit", async (e) => {
                e.preventDefault();
                form.classList.add("was-validated");

                if (!form.checkValidity()) return;

                const jsonData = Object.fromEntries(new FormData(form));

                try {
                    const res = await fetch("api/v1/resetPassword", {
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
                        const alertInfo = document.querySelector('#alertInfo');
                        if (alertInfo) {
                            alertInfo.textContent = success;
                            alertInfo.classList.add("alert", "alert-info"); // Add Bootstrap classes
                        }
                    } else {
                        Notiflix.Notify.failure(error || "Something wrong happened.");
                        const alertInfo = document.querySelector('#alertInfo');
                        if (alertInfo) {
                            alertInfo.textContent = error;
                            alertInfo.classList.add("alert", "alert-danger"); // Add Bootstrap classes
                        }
                    }
                } catch {
                    Notiflix.Notify.failure("An error occurred. Please try again.");
                }
            });
        });
    </script>
<?php endif; ?>

</body>

</html>