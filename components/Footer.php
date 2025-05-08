<?php
$pages = $database->select('pages', '*', [
    'pagePosition' => 'footer',
    'is_active' => 1
]);
?>
<!-- Footer 2 - Footer Component -->
<footer class="footer mt-5">
    <!-- Widgets - Footer Component -->
    <section class="py-4 py-md-5 py-xl-8 border-top text-white bg-dark">
        <div class="container overflow-hidden">
            <div class="row gy-4 gy-lg-0 justify-content-xl-between">
                <div class="col-12 col-md-4 col-lg-3 col-xl-2">
                    <div class="widget">
                        <a href="#!">
                            <img src="assets/images/main-logo-white.png" alt="Fayyaz Travels Logo" width="175" height="57">
                        </a>
                        <h6 class="text-light">Dedicated Service, Tailored for you </h6>
                        <p class="small">Founded on our own love of travel, Fayyaz Travels continues to welcome travelers consumed by wanderlust into the family, keeping that streak of passion burning bright.</p>
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-3 col-xl-2">
                    <div class="widget">
                        <h4 class="widget-title mb-4">Get in Touch</h4>
                        <p class="mb-1 small">Fayyaz Travels Pte. Ltd.</p>
                        <p class="mb-1 small">STB Licence: TA03463</p>
                        <p class="mb-1">
                            <a class="link-light text-decoration-none small" href="tel:+6562352900"> <i class="bi bi-telephone-fill"></i> : +65 6235 2900</a>
                        </p>
                        <p class="mb-0">
                            <a class="link-light text-decoration-none small" href="mailto:info@fayyaztravels.com"> <i class="bi bi-envelope-fill"></i> : info@fayyaztravels.com</a>
                        </p>
                        <p class="mb-0 text-center mt-3">
                            <img src="assets/images/stamp.png" alt="Visa Services" class="img-fluid d-block mx-auto" width="150px">
                        </p>
                    </div>
                </div>

                <div class="col-12 col-md-4 col-lg-3 col-xl-2">
                    <div class="widget">
                        <h4 class="widget-title mb-4">Learn More</h4>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <a target="_blank" href="https://fayyaztravels.com/about" class="link-light text-decoration-none">About Us</a>
                            </li>
                            <li class="mb-2">
                                <a target="_blank" href="https://fayyaztravels.com/contact" class="link-light text-decoration-none">Contact Us</a>
                            </li>
                            <li class="mb-2">
                                <a target="_blank" href="https://fayyaztravels.com/csr" class="link-light text-decoration-none">Corporate Social Responsibility</a>
                            </li>
                            <li class="mb-2">
                                <a target="_blank" href="https://fayyaztravels.com/testimonials" class="link-light text-decoration-none">Testimonials</a>
                            </li>
                            <li class="mb-2">
                                <a target="_blank" href="https://fayyaztravels.com/terms-and-conditions" class="link-light text-decoration-none">Terms and Conditions</a>
                            </li>
                            <li class="mb-0">
                                <a target="_blank" href="https://fayyaztravels.com/privacy-policy" class="link-light text-decoration-none">Privacy Policy</a>
                            </li>
                            <?php
                            foreach ($pages as $page):
                            ?>
                                <li class="mb-0  mt-2">
                                    <a href="pages/<?= $page['pageSlug']; ?>" class="link-light text-decoration-none"><?= $page['pageName']; ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <div class="col-12 col-lg-3 col-xl-4">
                    <div class="widget">
                        <h4 class="widget-title mb-4">Our Newsletter</h4>
                        <p class="mb-4">Subscribe to our newsletter to get our news & discounts delivered to you.</p>
                        <form id="subsForm">
                            <div class="row gy-4">
                                <div class="col-12">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white" id="email-newsletter-addon">
                                            <i class="bi bi-envelope-heart fs-4"></i>
                                        </span>
                                        <input type="email" name="subemail" class="form-control-lg form-control" id="email-newsletter" value="" placeholder="Email Address" aria-label="email-newsletter" aria-describedby="email-newsletter-addon" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-golden btn-lg rounded-pill" type="submit">Subscribe <i class="bi bi-arrow-up-right-circle"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Copyright - Footer Component -->
    <div class="py-4 py-md-5 py-xl-8 text-light bg-dark">
        <div class="container overflow-hidden">
            <div class="row gy-4 gy-md-0 align-items-md-center">
                <div class="col-xs-12 col-md-7 order-1 order-md-0">
                    <div class="credits text-secondary text-center text-md-start mt-2 fs-8">
                        Built for 🌍 by <a href="https://inncelerator.com" class="link-secondary text-decoration-none">Inncelerator</a> with <i class="bi bi-heart-fill text-danger"></i> in 🇸🇬 Singapore.
                    </div>
                    <div class="copyright text-center text-md-start">
                        <?= date('Y'); ?> &copy; Fayyaz Travels. All Rights Reserved.
                    </div>
                </div>

                <div class="col-xs-12 col-md-5 order-0 order-md-1">
                    <div class="social-media-wrapper">
                        <ul class="list-unstyled m-0 p-0 d-flex justify-content-center justify-content-md-end">
                            <li class="me-3">
                                <a href="https://www.facebook.com/FayyazTravels/" class="link-light">
                                    <i class="bi bi-facebook fs-4"></i>
                                </a>
                            </li>
                            <li class="me-3">
                                <a href="https://www.instagram.com/fayyaztravels/" class="link-light">
                                    <i class="bi bi-instagram fs-4"></i>
                                </a>
                            </li>
                            <li class="me-3">
                                <a href="https://x.com/FayyazTravels" class="link-light">
                                    <i class="bi bi-twitter-x fs-4"></i>
                                </a>
                            </li>
                            <li class="me-3">
                                <a href="https://sg.linkedin.com/company/fayyaz-travels-pte-ltd" class="link-light">
                                    <i class="bi bi-linkedin fs-4"></i>
                                </a>
                            </li>
                            <li class="me-3">
                                <a href="https://www.youtube.com/@fayyaztravels" class="link-light">
                                    <i class="bi bi-youtube fs-4"></i>
                                </a>
                            </li>
                            <li class="me-3">
                                <a href="https://www.pinterest.com/fayyaztravels/" class="link-light">
                                    <i class="bi bi-pinterest fs-4"></i>
                                </a>
                            </li>
                            <li class="me-3">
                                <a href="https://www.tiktok.com/@fayyaztravels" class="link-light">
                                    <i class="bi bi-tiktok fs-4"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <p class="text-center text-light small">Version MMXXV-II-XXIX: <a class="text-decoration-none text-golden" href=" changelog">Changelog</a> &bullet; Powered by <a target="_blank" class="text-decoration-none text-golden" href="https://inncelerator.com">Inncelerator</a></p>
                </div>
            </div>
        </div>
    </div>
</footer>
<?php
$currentPath = $_SERVER['REQUEST_URI'];

if (strpos($currentPath, 'country') !== false) {
    echo '<script src="assets/js/bottomBar.js"></script>';
}
?>


<!-- Bottom Bar -->
<!-- Mobile Bottom Navigation Bar -->
<?php

// Show bottom bar only if 'application' is NOT in the path
if (strpos($currentPath, 'application') === false): ?>
    <div class="mobile-bottom-bar d-block d-md-none fixed-bottom">
        <div class="container-fluid px-2">
            <div class="row g-0 text-center py-2">
                <?php
                // Check if we're on a country page to adjust columns
                $isCountryPage = strpos($currentPath, 'country') !== false;
                // Define column class based on whether we show share button or not
                $colClass = $isCountryPage ? 'col-20p' : 'col-3';
                ?>

                <div class="<?= $colClass ?>">
                    <a href="/visa/" class="text-decoration-none text-dark">
                        <i class="bi bi-house-door"></i>
                        <div class="nav-label">Home</div>
                    </a>
                </div>

                <div class="<?= $colClass ?>">
                    <a href="https://api.whatsapp.com/send/?phone=6594314389&text=Hi!%20I%20am%20interested%20in%20visa%20processing&type=phone_number&app_absent=0" class="text-decoration-none text-dark">
                        <i class="bi bi-whatsapp"></i>
                        <div class="nav-label">Support</div>
                    </a>
                </div>



                <!-- Share Button in Menu Bar -->
                <?php if ($isCountryPage): ?>
                    <div class="<?= $colClass ?>">
                        <a href="javascript:void(0);" class="text-decoration-none text-dark" onclick="handleShare()">
                            <i class="bi bi-box-arrow-up"></i>
                            <div class="nav-label">Share</div>
                        </a>
                    </div>
                <?php endif; ?>

                <div class="<?= $colClass ?>">
                    <?php if (!empty($_SESSION['user_id'])): ?>
                        <a href="applications" class="text-decoration-none text-dark">
                            <i class="bi bi-file-earmark-text"></i>
                            <div class="nav-label">Apps</div>
                        </a>
                    <?php else: ?>
                        <a href="auth/signup" class="text-decoration-none text-dark">
                            <i class="bi bi-person-plus"></i>
                            <div class="nav-label">Sign Up</div>
                        </a>
                    <?php endif; ?>
                </div>

                <div class="<?= $colClass ?>">
                    <?php if (empty($_SESSION['user_id'])): ?>
                        <a href="auth/login" class="text-decoration-none text-dark">
                            <i class="bi bi-box-arrow-in-right"></i>
                            <div class="nav-label">Login</div>
                        </a>
                    <?php else: ?>
                        <a href="logout" class="text-decoration-none text-dark">
                            <i class="bi bi-box-arrow-right"></i>
                            <div class="nav-label">Logout</div>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Share Modal for Fallback -->

<?php if (strpos($currentPath, 'country') !== false): ?>
    <div class="offcanvas offcanvas-bottom" tabindex="-1" id="shareModal" aria-labelledby="shareModalLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="shareModalLabel">Share this page</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body small">
            <div class="d-grid gap-2">
                <button class="btn btn-light border" onclick="copyLink()">
                    <i class="bi bi-clipboard me-1"></i> Copy Link
                </button>
                <a class="btn btn-success" target="_blank" id="whatsapp-share">
                    <i class="bi bi-whatsapp me-1"></i> WhatsApp
                </a>
                <a class="btn btn-primary" target="_blank" id="telegram-share">
                    <i class="bi bi-telegram me-1"></i> Telegram
                </a>
                <a class="btn btn-info text-white" target="_blank" id="facebook-share">
                    <i class="bi bi-facebook me-1"></i> Facebook
                </a>
                <a class="btn btn-primary" style="background-color: #0084ff;" target="_blank" id="messenger-share">
                    <i class="bi bi-messenger me-1"></i> Messenger
                </a>
                <a class="btn btn-warning text-dark" style="background-color: #00c300;" target="_blank" id="line-share">
                    <i class="bi bi-chat-dots me-1"></i> LINE
                </a>
                <a class="btn btn-info text-white" style="background-color: #1da1f2;" target="_blank" id="twitter-share">
                    <i class="bi bi-twitter me-1"></i> Twitter / X
                </a>
            </div>
        </div>
    </div>
<?php endif; ?>

<style>
    /* Custom 20% width column for 5-column layout */
    .col-20p {
        flex: 0 0 20%;
        max-width: 20%;
    }

    .mobile-bottom-bar {
        z-index: 1000;
        background: rgba(255, 255, 255, 0.8) !important;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 16px 16px 0 0;
        margin: 0 8px;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1) !important;
        border-top: 1px solid rgba(255, 255, 255, 0.5);
        transition: transform 0.3s ease-in-out;
    }

    .mobile-bottom-bar a {
        display: flex;
        flex-direction: column;
        align-items: center;
        color: var(--blue-dark) !important;
        transition: color 0.2s ease;
    }

    .mobile-bottom-bar a:hover {
        color: var(--golden) !important;
    }

    .mobile-bottom-bar a.text-primary {
        color: var(--golden) !important;
    }

    /* Hide on scroll down, show on scroll up */
    .mobile-bottom-bar.hidden {
        transform: translateY(100%);
    }

    /* Add padding to the bottom of the page to prevent content from being hidden behind the bar */
    @media (max-width: 767.98px) {
        body {
            padding-bottom: 70px;
        }
    }
</style>

<script src="assets/js/mailchimp.js"></script>