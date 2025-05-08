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

$metatags = new MetaTags();

$metatags
    ->description('Apply for your visa online with Fayyaz Travels – fast, secure, and hassle-free! Get quick visa approvals, real-time tracking, and expert support for tourist visas, business visas, and eVisas. Skip embassy queues and travel stress-free. Start your online visa application today!')
    ->meta('author', 'Fayyaz Travels')
    ->meta('robots', 'index, follow')
    ->image($url . '/og/index.php?title=' . urlencode('Simplifying Travel Visa Applications') . '&subtitle=' . urlencode('Smooth visa processing with expert guidance') . '&gradient=' . urlencode('sunset_meadow'))
    ->canonical('https://fayyaztravels.com/visa');

// Convert both to strings and concatenate
$seo = $schema . "\n" . $metatags;
// SEO END

// Output HTML head and scripts
echo html_head('Simplifying Travel Visa Applications', null, true, ['assets/css/home.css', 'https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.0/build/css/intlTelInput.css?token=54ae6ea63d08ea71'], false, false, $seo);

?>
<link rel="preload" as="image" href="assets/images/brown_flight.webp">
<link rel="preload" as="image" href="assets/images/fly-high.webp">

<!-- Navbar -->
<?php
if (isset($_SESSION['user_id'])) {

    require 'components/LoggedinNavbar.php';
} else {

    require 'components/Navbar.php';
}
?>
<!-- ./Navbar -->

<!-- Hero Section -->
<section class="container-fluid hero-section text-white" id="heroSection" style="display:none !important;">
    <div class="container h-100">
        <div class="row h-100 align-items-center justify-content-center">
            <div class="col-12 text-center">
                <h1>Simplifying Travel Visa Applications</h1>
                <h2 class="mb-4 h4 alterFont">Smooth visa processing with expert guidance</h2>
                <!-- Search Form -->
                <form action="search" method="get" class="mx-auto" id="searchForm" autocapitalize="off" autocomplete="off" autocorrect="off" spellcheck="false">
                    <div class="custom-search-card">
                        <div class="custom-input-wrapper">
                            <span class="custom-input-icon"><i class="bi bi-geo-alt-fill text-dark"></i></span>
                            <input autofocus type="text" id="searchDestination" class="custom-input" name="q" placeholder="Search a Country" required>
                            <div class="custom-buttons">
                                <button class="custom-btn-search rounded-pill" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                                <button class="custom-btn-voice d-block d-lg-none" type="button">
                                    <i class="bi bi-mic"></i>
                                </button>
                            </div>
                            <label for="searchDestination" class="custom-input-label">Where to?</label>
                        </div>
                    </div>
                </form>
                <!-- ./Search Form -->
                <div class="trending-places">
                    <strong class="me-2 mt-2">Trending places</strong>
                    <br class="d-block d-lg-none">
                    <a class="text-golden me-2 text-decoration-none" href="country/apply-for-singapore-visa-online">🇸🇬 Singapore</a>
                    <a class="text-golden me-2 text-decoration-none" href="country/apply-for-united-arab-emirates-visa-online">🇦🇪 United Arab Emirates</a>
                </div>

            </div>
        </div>
    </div>
</section>


<!-- Shuffle Section -->
<!-- <section class="container my-3">
    <div class="row">
        <div class="col-12 d-flex justify-content-center">
            <div class="scroll-nav-container rounded-3 border p-1">
                <ul class="custom-nav nav nav-pills">
                    <li class="custom-nav-item nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Popular</a>
                    </li>
                    <li class="custom-nav-item nav-item">
                        <a class="nav-link" href="#">In a Week</a>
                    </li>
                    <li class="custom-nav-item nav-item">
                        <a class="nav-link" href="#">In a Month</a>
                    </li>
                    <li class="custom-nav-item nav-item">
                        <a class="nav-link" href="#">Seasonal</a>
                    </li>
                    <li class="custom-nav-item nav-item">
                        <a class="nav-link" href="#">Schengen Visa</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section> -->

<!-- Places section -->
<section class="container mt-3">
    <?php require 'components/VisaCard.php'; ?>
</section>


<!-- Read Reviews -->
<?php require 'components/readReviews.php'; ?>


<!-- Stats Section -->
<section class="stats-section position-relative my-5 py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h2 class="fw-bold">Get your visa approved with confidence</h2>
            </div>
        </div>
    </div>
    <div class="stats-overlay"></div>
    <div class="container">
        <div class="row justify-content-center align-items-center text-center">

            <!-- Google Rating -->
            <div class="col-sm-6 col-md-3 rating-container">
                <div class="rating-value">4.8</div>
                <div class="stars">
                    <svg class="star-icon" viewBox="0 0 24 24">
                        <path d="M12 2l2.4 7.4h7.6l-6 4.6 2.3 7-6.3-4.6-6.3 4.6 2.3-7-6-4.6h7.6z" />
                    </svg>
                    <svg class="star-icon" viewBox="0 0 24 24">
                        <path d="M12 2l2.4 7.4h7.6l-6 4.6 2.3 7-6.3-4.6-6.3 4.6 2.3-7-6-4.6h7.6z" />
                    </svg>
                    <svg class="star-icon" viewBox="0 0 24 24">
                        <path d="M12 2l2.4 7.4h7.6l-6 4.6 2.3 7-6.3-4.6-6.3 4.6 2.3-7-6-4.6h7.6z" />
                    </svg>
                    <svg class="star-icon" viewBox="0 0 24 24">
                        <path d="M12 2l2.4 7.4h7.6l-6 4.6 2.3 7-6.3-4.6-6.3 4.6 2.3-7-6-4.6h7.6z" />
                    </svg>
                    <svg class="star-icon half-star" viewBox="0 0 24 24">
                        <path d="M12 2l2.4 7.4h7.6l-6 4.6 2.3 7-6.3-4.6-6.3 4.6 2.3-7-6-4.6h7.6z" />
                    </svg>
                </div>
                <div class="rating-text">Google Rating</div>
            </div>

            <!-- Visa Approval Rate -->
            <div class="col-sm-6 col-md-3 rating-container">
                <div class="svg-container">
                    <svg class="progress-circle" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="45" fill="none" stroke="#e0e0e0" stroke-width="10" />
                        <path d="M 50,50 m -45,0 a 45,45 0 1,1 90,0 a 45,45 0 1,1 -90,0"
                            fill="none" stroke="#009a5d" stroke-width="10" stroke-dasharray="282.6" stroke-dashoffset="2.8" />
                        <text x="50" y="55" text-anchor="middle" font-size="18" font-weight="bold">99.5%</text>
                    </svg>
                </div>
                <div class="rating-text">Visa Approval Rate</div>
            </div>

            <!-- Visas Processed (Meter Style) -->
            <div class="col-sm-6 col-md-3 rating-container">
                <div class="flip-counter">
                    <div class="digit">1</div>
                    <div class="digit">0</div>
                    <div class="digit">0</div>
                    <div class="digit">0</div>
                    <div class="digit">0</div>
                    <div class="digit plus">+</div>
                </div>
                <div class="rating-text mt-2">Visas Processed</div>
            </div>

        </div>
    </div>
</section>



<!-- Why Book With Us -->
<?php require('components/whyBookWithUs.php'); ?>
<!-- ./Why Book With Us -->

<!-- How it Works section -->
<section class="visa-section my-5" id="how-it-works">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center section-title">
                <h2 class="fw-bold mt-5">Expert Visa Application with Fayyaz Travels</h2>
                <p class="subtitle fs-5">Your hassle-free journey begins with our 4-step process</p>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="step-card">
                    <div class="card-body text-center">
                        <div class="step-number">1</div>
                        <div class="step-icon">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <h3 class="step-title">Quick Application</h3>
                        <p class="step-description">Fill out your details & make a secure payment</p>
                        <ul class="feature-list">
                            <li>User-friendly form</li>
                            <li>Secure payment gateway</li>
                            <li>Saves your progress</li>
                        </ul>
                    </div>
                    <div class="card-glass-effect"></div>
                    <div class="card-shine"></div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3 mb-4">
                <div class="step-card">
                    <div class="card-body text-center">
                        <div class="step-number">2</div>
                        <div class="step-icon">
                            <i class="bi bi-cpu"></i>
                        </div>
                        <h3 class="step-title">AI-Powered Processing</h3>
                        <p class="step-description">Speedy documentation with advanced AI</p>
                        <ul class="feature-list">
                            <li>Automated verification</li>
                            <li>Smart form completion</li>
                            <li>Rapid processing</li>
                        </ul>
                    </div>
                    <div class="card-glass-effect"></div>
                    <div class="card-shine"></div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3 mb-4">
                <div class="step-card">
                    <div class="card-body text-center">
                        <div class="step-number">3</div>
                        <div class="step-icon">
                            <i class="bi bi-person-check"></i>
                        </div>
                        <h3 class="step-title">Expert Review</h3>
                        <p class="step-description">Double checked by our specialists and in-house AI</p>
                        <ul class="feature-list">
                            <li>Human and in-house AI expertise</li>
                            <li>99.5% approval rate</li>
                            <li>Error-free applications</li>
                        </ul>
                    </div>
                    <div class="card-glass-effect"></div>
                    <div class="card-shine"></div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3 mb-4">
                <div class="step-card">
                    <div class="card-body text-center">
                        <div class="step-number mb-3">4</div>
                        <div class="step-icon">
                            <i class="bi bi-airplane"></i>
                        </div>
                        <h3 class="step-title">Visa Delivery</h3>
                        <p class="step-description">Sit back as we deliver your visa on time</p>
                        <ul class="feature-list">
                            <li>On-time delivery</li>
                            <li>Status tracking</li>
                            <li>Travel support</li>
                        </ul>
                    </div>
                    <div class="card-glass-effect"></div>
                    <div class="card-shine"></div>
                </div>
            </div>
        </div>

        <div class="row cta-container">
            <div class="col-12 text-center">
                <!-- <button class="btn btn-primary btn-lg cta-button">Start Your Application Now</button> -->
                <p class="mt-3 fs-4 text-muted"><i class="bi bi-people"></i> Join thousands of travelers who trust Fayyaz Travels</p>
            </div>
        </div>
    </div>
</section>








<!-- Services -->
<section class="container my-5">
    <div class="row">
        <div class="col">
            <div class="text-center section-title">
                <h2 class="fw-bold mt-5">Worldwide Visa Solutions</h2>
                <p class="subtitle fs-5">Expert visa services for stress-free global travel</p>
            </div>
            <!-- Top row of visa types -->
            <div class="row g-4 mb-4">
                <div class="col-md-6 col-lg-3">
                    <div class="visaa-card">
                        <div class="cardd-body">
                            <div class="visaa-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-circle-fill" viewBox="0 0 16 16">
                                    <circle cx="8" cy="8" r="8" />
                                </svg>
                            </div>
                            <h5 class="visaa-title">30 days Tourist Visit</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="visaa-card">
                        <div class="cardd-body">
                            <div class="visaa-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-circle-fill" viewBox="0 0 16 16">
                                    <circle cx="8" cy="8" r="8" />
                                </svg>
                            </div>
                            <h5 class="visaa-title">60 days Tourist Visit</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="visaa-card">
                        <div class="cardd-body">
                            <div class="visaa-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-circle-fill" viewBox="0 0 16 16">
                                    <circle cx="8" cy="8" r="8" />
                                </svg>
                            </div>
                            <h5 class="visaa-title">Single Entry Tourist Visa</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="visaa-card">
                        <div class="cardd-body">
                            <div class="visaa-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-circle-fill" viewBox="0 0 16 16">
                                    <circle cx="8" cy="8" r="8" />
                                </svg>
                            </div>
                            <h5 class="visaa-title">Multi Entry Tourist Visa</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom row of visa types -->
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="visaa-card">
                        <div class="cardd-body">
                            <div class="visaa-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-circle-fill" viewBox="0 0 16 16">
                                    <circle cx="8" cy="8" r="8" />
                                </svg>
                            </div>
                            <h5 class="visaa-title">Visa Extensions for Tourists</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="visaa-card">
                        <div class="cardd-body">
                            <div class="visaa-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-circle-fill" viewBox="0 0 16 16">
                                    <circle cx="8" cy="8" r="8" />
                                </svg>
                            </div>
                            <h5 class="visaa-title">Corporate Group Visas</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="visaa-card">
                        <div class="cardd-body">
                            <div class="visaa-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-circle-fill" viewBox="0 0 16 16">
                                    <circle cx="8" cy="8" r="8" />
                                </svg>
                            </div>
                            <h5 class="visaa-title">Temporary Stay Visas</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>




<!-- FAQ -->
<div class="container-fluid bg-light">
    <section class="container my-5">
        <div class="row">
            <div class="col">
                <div class="text-center section-title">
                    <h2 class="fw-bold mt-5">Visa Questions? We've Got You Covered</h2>
                    <p class="subtitle fs-5">Fayyaz Travels ensures fast, cost-effective, and hassle-free visa services</p>
                </div>
                <div class="row justify-content-center">
                    <?php require 'components/visa_faq.php'; ?>
                </div>
            </div>
    </section>
</div>

<!-- Hilarious section - Modern Redesign -->
<section class="container my-5">
    <div class="row">
        <div class="col-12">
            <div class="card p-4 rounded-4 border-0">
                <div class="card-body">
                    <div class="row align-items-center">
                        <!-- First Column: Text and Input Form -->
                        <div class="col-lg-7 mb-4 mb-lg-0">
                            <h2 class="fw-bold mb-3">Ready to get started? Enter your travel destination</h2>
                            <p class="text-muted fs-5 mb-4">🚀 Visa Process Made Easy<br>
                                📋 Get Your Checklist<br>
                                💥 Sign Up FREE!</p>
                            <div class="row justify-content-left">
                                <div class="col-lg-10">
                                    <form action="search" method="get" class="mb-4">
                                        <div class="input-group input-group-lg rounded-pill bg-white" style="border: 1px solid var(--blue);">
                                            <span class="input-group-text bg-white border-0 rounded-start-pill ps-4">
                                                <i class="bi bi-airplane-fill fs-4" style="color: var(--blue);"></i>
                                            </span>
                                            <input type="text" name="q" class="form-control border-0 shadow-none py-3" placeholder="Search a country..." required>
                                            <button class="btn btn-blue rounded-end-pill px-4">
                                                <i class="bi bi-search"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Second Column: Benefits Cards -->
                        <div class="col-lg-5">
                            <div class="row g-3">
                                <!-- First Card -->
                                <div class="col-md-6 col-lg-12">
                                    <div class="card bg-white border-0 rounded-4 shadow-sm h-100 hover-scale">
                                        <div class="card-body p-4 d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                                                <i class="bi bi-lightning-charge-fill text-primary fs-3"></i>
                                            </div>
                                            <div>
                                                <h5 class="fw-bold mb-1">Faster than pizza delivery</h5>
                                                <p class="text-muted small mb-0">No paperwork, no hassle</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Second Card -->
                                <div class="col-md-6 col-lg-12">
                                    <div class="card bg-white border-0 rounded-4 shadow-sm h-100 hover-scale">
                                        <div class="card-body p-4 d-flex align-items-center">
                                            <div class="bg-success bg-opacity-10 rounded-3 p-3 me-3">
                                                <i class="bi bi-phone-fill text-success fs-3"></i>
                                            </div>
                                            <div>
                                                <h5 class="fw-bold mb-1">Easier than finding Wi-Fi</h5>
                                                <p class="text-muted small mb-0">Simple 3-step process</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Third Card -->
                                <div class="col-md-6 col-lg-12">
                                    <div class="card bg-white border-0 rounded-4 shadow-sm h-100 hover-scale">
                                        <div class="card-body p-4 d-flex align-items-center">
                                            <div class="bg-warning bg-opacity-10 rounded-3 p-3 me-3">
                                                <i class="bi bi-shield-lock-fill text-warning fs-3"></i>
                                            </div>
                                            <div>
                                                <h5 class="fw-bold mb-1">Safer than your search history</h5>
                                                <p class="text-muted small mb-0">End-to-end encrypted</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section position-relative my-5 py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h2 class="fw-bold">Get your visa approved with confidence</h2>
            </div>
        </div>
    </div>
    <div class="stats-overlay"></div>
    <div class="container">
        <div class="row justify-content-center align-items-center text-center">

            <!-- Google Rating -->
            <div class="col-sm-6 col-md-3 rating-container">
                <div class="rating-value">4.8</div>
                <div class="stars">
                    <svg class="star-icon" viewBox="0 0 24 24">
                        <path d="M12 2l2.4 7.4h7.6l-6 4.6 2.3 7-6.3-4.6-6.3 4.6 2.3-7-6-4.6h7.6z" />
                    </svg>
                    <svg class="star-icon" viewBox="0 0 24 24">
                        <path d="M12 2l2.4 7.4h7.6l-6 4.6 2.3 7-6.3-4.6-6.3 4.6 2.3-7-6-4.6h7.6z" />
                    </svg>
                    <svg class="star-icon" viewBox="0 0 24 24">
                        <path d="M12 2l2.4 7.4h7.6l-6 4.6 2.3 7-6.3-4.6-6.3 4.6 2.3-7-6-4.6h7.6z" />
                    </svg>
                    <svg class="star-icon" viewBox="0 0 24 24">
                        <path d="M12 2l2.4 7.4h7.6l-6 4.6 2.3 7-6.3-4.6-6.3 4.6 2.3-7-6-4.6h7.6z" />
                    </svg>
                    <svg class="star-icon half-star" viewBox="0 0 24 24">
                        <path d="M12 2l2.4 7.4h7.6l-6 4.6 2.3 7-6.3-4.6-6.3 4.6 2.3-7-6-4.6h7.6z" />
                    </svg>
                </div>
                <div class="rating-text">Google Rating</div>
            </div>

            <!-- Visa Approval Rate -->
            <div class="col-sm-6 col-md-3 rating-container">
                <div class="svg-container">
                    <svg class="progress-circle" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="45" fill="none" stroke="#e0e0e0" stroke-width="10" />
                        <path d="M 50,50 m -45,0 a 45,45 0 1,1 90,0 a 45,45 0 1,1 -90,0"
                            fill="none" stroke="#009a5d" stroke-width="10" stroke-dasharray="282.6" stroke-dashoffset="2.8" />
                        <text x="50" y="55" text-anchor="middle" font-size="18" font-weight="bold">99.5%</text>
                    </svg>
                </div>
                <div class="rating-text">Visa Approval Rate</div>
            </div>

            <!-- Visas Processed (Meter Style) -->
            <div class="col-sm-6 col-md-3 rating-container">
                <div class="flip-counter">
                    <div class="digit">1</div>
                    <div class="digit">0</div>
                    <div class="digit">0</div>
                    <div class="digit">0</div>
                    <div class="digit">0</div>
                    <div class="digit plus">+</div>
                </div>
                <div class="rating-text mt-2">Visas Processed</div>
            </div>

        </div>
    </div>
</section>


<!-- Refer and Earn Section -->
<!-- <section class="my-5 py-5">
                </?php require 'components/leaderboard.php'; ?>
            </div>
        </div>
    </div>
</section> -->

<!-- Get Reviews -->
<?php require 'components/Reviews.php'; ?>
<!-- Listening modal -->
<div class="modal fade" id="listeningModal" tabindex="-1" aria-labelledby="listeningModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center mt-2">
                <p>Listening...</p>
            </div>
        </div>
    </div>
</div>
<!-- Callback -->

<!-- ./callback -->

<section class="py-5 container-fluid bg-light">
    <div class="container">
        <div class="row"> <!-- Fixed missing tag here -->
            <div class="col-12">
                <!-- SEO -->
                <header>
                    <h1 class="h6">#1 Trusted Visa Service for Hassle-Free Global Travel</h1>
                    <p>Planning an international trip? With years of experience in <strong>visa processing</strong>, we make
                        <strong>visa applications simple, secure, and stress-free</strong>. Whether you're traveling for <strong>tourism, business, study, or work</strong>,
                        our expert team assists with visas for <strong>50+ countries</strong>, including the <strong>UAE, USA, UK, Australia, Canada, Singapore, and Schengen nations</strong>.
                    </p>
                    <p>We provide <strong>expert document verification</strong>, direct <strong>embassy submission assistance</strong>, and <strong>real-time tracking</strong>
                        to ensure a smooth visa approval process. Our trusted platform is backed by <strong>certified visa consultants</strong> and strict <strong>security measures</strong>
                        to keep your data safe.</p>
                </header>

                <section>
                    <h2 class="h6">Why Travellers Trust Us?</h2>
                    <ul>
                        <li><strong>Years of Expertise in Visa Processing</strong> – Our team of <em>visa specialists</em> ensures accurate and efficient application handling.</li>
                        <li><strong>Fast & Reliable Visa Services</strong> – Assistance for <em>tourist visas, business visas, student visas, and work permits</em> for over <strong>50 countries</strong>.</li>
                        <li><strong>Urgent Visa Processing</strong> – Need a last-minute visa? We help with <em>fast-track applications</em> for <strong>Dubai, Singapore, the UK, and Schengen countries</strong>.</li>
                        <li><strong>Expert Document Verification</strong> – Reduce the chances of <em>visa rejection</em> with a thorough pre-check before embassy submission.</li>
                        <li><strong>Live Visa Status Updates</strong> – Track your <strong>Schengen visa, US visa, UK visa, Canada visa, Australia visa</strong>, and more in real-time.</li>
                        <li><strong>E-Visa Applications in Minutes</strong> – Quickly apply for <strong>e-visas</strong> to <em>Thailand, Saudi Arabia, Japan, Turkey, Malaysia</em>, and other major destinations.</li>
                        <li><strong>100% Secure & Compliant</strong> – Our platform follows <em>strict security standards</em> with <strong>encrypted document uploads</strong> and <strong>certified visa consultants</strong> ensuring accuracy.</li>
                    </ul>
                </section>

                <section>
                    <h2 class="h6">Who We Are – A Trusted Visa Partner</h2>
                    <p>Our platform is built on <strong>transparency, accuracy, and trust</strong>. We collaborate with <strong>government-approved agents</strong>
                        and adhere to <strong>embassy guidelines</strong> to provide accurate visa information and support. With <strong>thousands of successful applications</strong>,
                        we've earned the trust of travellers worldwide.</p>

                    <h3 class="h6">Why It Matters?</h3>
                    <ul>
                        <li>Our expertise ensures <strong>higher visa approval rates</strong> with <strong>error-free documentation</strong>.</li>
                        <li>We stay updated with the <strong>latest visa policies and embassy requirements</strong> to avoid common application mistakes.</li>
                        <li>Our <strong>customer support team</strong> is available to guide you through every step of the visa application process.</li>
                    </ul>
                </section>

                <section>
                    <h2 class="h6">Popular Destinations We Cover</h2>
                    <p><strong>UAE · USA · UK · Australia · New Zealand · India · Singapore · Canada · Saudi Arabia · Japan · France · Germany · Italy · Spain · Thailand</strong> and <strong>50+ other countries</strong>.</p>
                </section>

                <!-- SEO -->
            </div>
        </div> <!-- Closing the row div -->
    </div>
</section>

<!-- Bottom Bar -->


<!-- Footer -->
<?php require 'components/Footer.php'; ?>


<?php
echo html_scripts(
    includeJQuery: false,
    includeBootstrap: true,
    customScripts: [],
    includeSwal: false,
    includeNotiflix: true
);
?>


<script>
    if ('webkitSpeechRecognition' in window) {
        const recognition = new webkitSpeechRecognition();
        recognition.continuous = false;
        recognition.lang = 'en-US';
        recognition.interimResults = false;

        // Select modal elements
        const modalElement = document.getElementById('listeningModal');
        const modal = new bootstrap.Modal(modalElement);
        const modalBody = modalElement.querySelector('.modal-body p');
        const voiceButton = document.querySelector('.custom-btn-voice');

        // Event listener for when the modal is fully shown
        modalElement.addEventListener('shown.bs.modal', event => {
            modalBody.textContent = "Listening...";
            recognition.start(); // Start recognition only after modal is fully visible
        });

        // Event listener for the button to open modal
        voiceButton.addEventListener('click', function() {
            modal.show();
        });

        // When speech is detected
        recognition.onresult = function(event) {
            const spokenWord = event.results[0][0].transcript;
            modalBody.textContent = `Heard: "${spokenWord}"`;

            setTimeout(() => {
                modal.hide();
                window.location.href = `search?q=${encodeURIComponent(spokenWord)}`;
            }, 1000);
        };

        // Handle no speech detected
        recognition.onspeechend = function() {
            modalBody.textContent = "No speech detected";
            setTimeout(() => modal.hide(), 1000);
        };

        // Handle errors
        recognition.onerror = function(event) {
            modalBody.textContent = "Listening failed. Please try again.";
            setTimeout(() => modal.hide(), 1500);
            Notiflix.Notify.failure('Sorry, there was an error recognizing your voice.');
        };

        // Handle modal close event
        modalElement.addEventListener('hidden.bs.modal', event => {
            recognition.stop(); // Ensure recognition stops when modal is closed
            console.log("Modal closed. Speech recognition stopped.");
        });
    } else {
        // Hide voice button if speech recognition is not supported
        const voiceButton = document.querySelector('.custom-btn-voice');
        voiceButton.style.display = 'none';
        Notiflix.Notify.failure('Your browser does not support voice recognition.');
    }
</script>


<script>
    // How it works
    document.addEventListener("DOMContentLoaded", function() {
        const howItWorksLink = document.querySelector('.nav-link[href*="how-it-works"]');
        if (howItWorksLink) {
            howItWorksLink.addEventListener("click", function(event) {
                event.preventDefault();
                const target = document.querySelector("#how-it-works");
                if (target) {
                    window.scrollTo({
                        top: target.offsetTop - 50,
                        behavior: "smooth"
                    });
                }
            });
        }
    });
</script>

<script>
    // JavaScript to show hero section after page load
    window.addEventListener('load', function() {
        const heroSection = document.getElementById('heroSection');
        const travelDestinationDiv = document.getElementById('travelDestinationDiv');

        if (heroSection) {
            heroSection.style.display = 'block';
        }
        if (travelDestinationDiv) {
            travelDestinationDiv.style.display = 'block';
        }
    });
</script>

<!-- callback -->
<script>
    let popoverInstance;
    document.addEventListener("DOMContentLoaded", function() {
        const callbackIcon = document.getElementById('callbackIcon');

        if (callbackIcon) {
            popoverInstance = new bootstrap.Popover(callbackIcon, {
                trigger: 'manual',
                sanitize: false,
                html: true,
                placement: 'left',
                container: 'body'
            });

            // Show popover on click
            callbackIcon.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (popoverInstance) {
                    popoverInstance.show();
                    updatePopoverPosition();

                    setTimeout(() => {
                        const popoverElement = document.querySelector('.popover');
                        if (popoverElement) {
                            const closeButton = popoverElement.querySelector('.btn-close');
                            if (closeButton) {
                                closeButton.addEventListener('click', hidePopover);
                            }
                        }
                    }, 100);
                }
            });

            // Hide popover when clicking outside
            document.addEventListener('click', function(event) {
                const popoverElement = document.querySelector('.popover');
                if (popoverInstance && !callbackIcon.contains(event.target) && (!popoverElement || !popoverElement.contains(event.target))) {
                    hidePopover();
                }
            });

            // Fix: Reposition popover dynamically when scrolling or resizing
            window.addEventListener('scroll', updatePopoverPosition, true);
            window.addEventListener('resize', updatePopoverPosition);
        }
    });

    function hidePopover() {
        if (popoverInstance) {
            popoverInstance.hide();
        }
    }

    function updatePopoverPosition() {
        const popoverElement = document.querySelector('.popover');
        const callbackIcon = document.getElementById('callbackIcon');

        if (popoverElement && callbackIcon) {
            const rect = callbackIcon.getBoundingClientRect();
            popoverElement.style.top = `${rect.top + window.scrollY}px`;
            popoverElement.style.left = `${rect.left + window.scrollX}px`;

            // Force Bootstrap to update positioning
            const popover = bootstrap.Popover.getInstance(callbackIcon);
            if (popover) {
                popover.update();
            }
        }
    }
</script>

<!-- faq -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const faqButtons = document.querySelectorAll(".faq-question");

        faqButtons.forEach(button => {
            const targetId = button.getAttribute("data-bs-target");
            if (!targetId) return;

            const targetCollapse = document.querySelector(targetId);
            if (!targetCollapse) return;

            const icon = button.querySelector("i");
            if (!icon) return;

            // Listen for Bootstrap collapse events
            targetCollapse.addEventListener("show.bs.collapse", function() {
                // Close other open collapses
                faqButtons.forEach(otherButton => {
                    if (otherButton !== button) {
                        const otherTargetId = otherButton.getAttribute("data-bs-target");
                        if (!otherTargetId) return;

                        const otherCollapse = document.querySelector(otherTargetId);
                        if (!otherCollapse) return;

                        if (otherCollapse.classList.contains('show')) {
                            otherCollapse.classList.remove('show');
                            const collapseInstance = bootstrap.Collapse.getInstance(otherCollapse);
                            if (collapseInstance) {
                                collapseInstance.hide();
                            }
                            const otherIcon = otherButton.querySelector("i");
                            if (otherIcon) {
                                otherIcon.classList.remove("bi-dash-circle");
                                otherIcon.classList.add("bi-plus-circle");
                            }
                        }
                    }
                });

                icon.classList.remove("bi-plus-circle");
                icon.classList.add("bi-dash-circle");
            });

            targetCollapse.addEventListener("hide.bs.collapse", function() {
                icon.classList.remove("bi-dash-circle");
                icon.classList.add("bi-plus-circle");
            });
        });
    });
</script>


<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.0/build/js/intlTelInput.min.js"></script>
<script src="assets/js/intl_home.js"></script>
<script src="assets/js/fetchCountry.js"></script>
<script>
    function requestCallback() {
        const mobileNumber = document.getElementById("mobile_home")?.value?.trim();
        const submitButton = document.getElementById("submit_button");

        if (!mobileNumber) {
            Notiflix.Notify.failure("Please enter your mobile number.");
            return;
        }

        // Validate mobile number format
        const phoneRegex = /^[0-9]{10,15}$/;
        if (!phoneRegex.test(mobileNumber)) {
            Notiflix.Notify.failure("Please enter a valid mobile number.");
            return;
        }

        if (!submitButton) return;

        // Disable the button and show "Please wait..."
        submitButton.disabled = true;
        submitButton.textContent = "Please wait...";

        // fetch country code
        myCountryCode((countryCode, callingCode) => {
            console.log(`Country Code: ${countryCode}`);
            console.log(`Calling Code: ${callingCode}`);

            // Send request to the server
            fetch("api/v1/request_callback", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        mobileNumber: mobileNumber,
                        countryCode: callingCode
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (!submitButton) return;

                    // Re-enable the button and reset text
                    submitButton.disabled = false;
                    submitButton.textContent = "Call Me";

                    if (data.status === "success") {
                        Notiflix.Notify.success(data.message || "Callback request successful.");
                        const mobileInput = document.getElementById("mobile_home");
                        if (mobileInput) {
                            mobileInput.value = '';
                        }
                    } else {
                        Notiflix.Notify.failure(data.error || "Callback request failed. Please try again.");
                    }
                })
                .catch(error => {
                    if (!submitButton) return;

                    // Re-enable the button and reset text
                    submitButton.disabled = false;
                    submitButton.textContent = "Call Me";

                    Notiflix.Notify.failure("An error occurred. Please try again.");
                    console.error("Error:", error);
                });
        });
    }
</script>

<!-- Add scripts from Navbar.php -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 10) {
                navbar.classList.add('scrolled');
                if (document.getElementById('navbar-logo')) {
                    document.getElementById('navbar-logo').style.height = '36px';
                }
            } else {
                navbar.classList.remove('scrolled');
                if (document.getElementById('navbar-logo')) {
                    document.getElementById('navbar-logo').style.height = '42px';
                }
            }
        });

        // Desktop search toggle
        const searchToggle = document.getElementById('searchToggle');
        const searchOverlay = document.getElementById('searchOverlay');
        const closeSearch = document.getElementById('closeSearch');

        if (searchToggle && searchOverlay && closeSearch) {
            searchToggle.addEventListener('click', function() {
                searchOverlay.classList.add('active');
                setTimeout(() => {
                    document.getElementById('navbarSearch').focus();
                }, 300);
            });

            closeSearch.addEventListener('click', function() {
                searchOverlay.classList.remove('active');
            });
        }

        // Mobile search toggle
        const mobileSearchToggle = document.getElementById('mobileSearchToggle');
        const mobileSearchOverlay = document.getElementById('mobileSearchOverlay');
        const closeMobileSearch = document.getElementById('closeMobileSearch');

        if (mobileSearchToggle && mobileSearchOverlay && closeMobileSearch) {
            mobileSearchToggle.addEventListener('click', function() {
                mobileSearchOverlay.classList.add('active');
            });

            closeMobileSearch.addEventListener('click', function() {
                mobileSearchOverlay.classList.remove('active');
            });
        }

        // Close search on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (searchOverlay) searchOverlay.classList.remove('active');
                if (mobileSearchOverlay) mobileSearchOverlay.classList.remove('active');
            }
        });
    });
</script>


</body>

</html>