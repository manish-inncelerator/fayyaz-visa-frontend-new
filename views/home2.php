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
        'name'       => 'Fayyaz Travels',
        'legalName'  => 'Fayyaz Travels Pte Ltd',
        'url'        => 'https://fayyaztravels.com/visa',
        'logo'       => 'https://fayyaztravels.com/uploads/images/main-logo.png',
        'contactPoint' => new Thing('ContactPoint', [
            'telephone'   => '+65 6235 2900',
            'email'       => 'info@fayyaztravels.com',
            'contactType' => 'customer service',
        ]),
        'address' => new Thing('PostalAddress', [
            'streetAddress'   => '435 Orchard Rd, #11-00 Wisma Atria',
            'addressLocality' => 'Singapore',
            'postalCode'      => '238877',
            'addressCountry'  => 'SG',
        ]),
        'sameAs'     => [
            'https://www.facebook.com/FayyazTravels/',
            'https://x.com/FayyazTravels',
            'https://www.instagram.com/fayyaztravels/',
            'https://sg.linkedin.com/company/fayyaz-travels-pte-ltd',
            'https://www.youtube.com/@fayyaztravels',
            'https://www.pinterest.com/fayyaztravels/',
            'https://www.tiktok.com/@fayyaztravels',
        ],
        'potentialAction' => new Thing('SearchAction', [
            'target'       => 'https://fayyaztravels.com/visa/search?q={search_term_string}',
            'query-input'  => 'required name=search_term_string',
        ])
    ])
);



$metatags = new MetaTags();

$metatags
    ->title('Simplifying Travel Visa Applications')
    ->description('Apply for your visa online with Fayyaz Travels – fast, secure, and hassle-free! Get quick visa approvals, real-time tracking, and expert support for tourist visas, business visas, and eVisas. Skip embassy queues and travel stress-free. Start your online visa application today!')
    ->meta('author', 'Fayyaz Travels')
    ->meta('robots', 'index, follow')
    ->meta('keywords', 'online visa application, tourist visa, business visa, eVisa, Fayyaz Travels, fast visa processing, travel visa support')

    // Open Graph Meta
    ->meta('og:title', 'Apply for Your Visa Online | Fayyaz Travels')
    ->meta('og:description', 'Fast, secure, and hassle-free visa application. Expert support for tourist, business, and eVisas. Track your visa in real-time!')
    ->meta('og:image', $url . '/og/index.php?title=' . urlencode('Simplifying Travel Visa Applications') . '&subtitle=' . urlencode('Smooth visa processing with expert guidance') . '&gradient=' . urlencode('sunset_meadow'))
    ->meta('og:url', 'https://fayyaztravels.com/visa')
    ->meta('og:type', 'website')
    ->meta('og:locale', 'en_US')

    // Twitter Card Meta
    ->meta('twitter:card', 'summary_large_image')
    ->meta('twitter:title', 'Apply for Your Visa Online | Fayyaz Travels')
    ->meta('twitter:description', 'Get expert support and fast approvals for your tourist, business, or eVisa. Apply online with Fayyaz Travels.')
    ->meta('twitter:image', $url . '/og/index.php?title=' . urlencode('Simplifying Travel Visa Applications') . '&subtitle=' . urlencode('Smooth visa processing with expert guidance') . '&gradient=' . urlencode('sunset_meadow'))

    ->canonical('https://fayyaztravels.com/visa');


// Convert both to strings and concatenate
$seo = $schema . "\n" . $metatags;
// SEO END

// Output HTML head and scripts
echo html_head('Simplifying Travel Visa Applications', null, true, ['assets/css/home.css', 'https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.0/build/css/intlTelInput.css?token=54ae6ea63d08ea71', 'assets/css/stats.css', 'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css'], false, false, $seo);


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
<!-- Desktop Hero Section - Visible only on larger screens -->
<section class="container-fluid hero-section text-white d-none d-lg-block" id="heroSectionDesktop">
    <div class="vs25FloatingShape vs25Shape1"></div>
    <div class="vs25FloatingShape vs25Shape2"></div>

    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-up">
                <h1 class="display-4 fw-bold mb-3">Simplifying Travel <span class="text-warning">Visa Applications</span></h1>
                <h2 class="mb-4 h5 fw-light">Smooth visa processing with expert guidance at every step</h2>

                <!-- Search Form -->
                <div class="search-glass-card mb-4">
                    <form action="search" method="get" id="searchFormDesktop" autocapitalize="off" autocomplete="off" autocorrect="off" spellcheck="false">
                        <div class="search-wrapper position-relative">
                            <i class="bi bi-geo-alt-fill position-absolute search-icon"></i>
                            <input type="text" id="searchDestinationDesktop" class="form-control form-control-lg rounded-pill ps-5" name="q" placeholder="Where do you want to travel?" required>
                            <button class="btn btn-warning rounded-circle search-btn" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Trending Places -->
                <div class="trending-destinations mb-4">
                    <span class="badge bg-light text-dark me-2 mb-2">Popular Destinations:</span>
                    <a class="badge bg-warning text-dark me-2 mb-2 text-decoration-none" href="country/apply-for-singapore-visa-online">
                        <i class="bi bi-airplane-fill me-1"></i>Singapore
                    </a>
                    <a class="badge bg-warning text-dark me-2 mb-2 text-decoration-none" href="country/apply-for-united-arab-emirates-visa-online">
                        <i class="bi bi-airplane-fill me-1"></i>United Arab Emirates
                    </a>
                    <a class="badge bg-warning text-dark me-2 mb-2 text-decoration-none" href="country/apply-for-schengen-visa-online">
                        <i class="bi bi-airplane-fill me-1"></i>Schengen
                    </a>
                </div>

                <!-- Stats Cards -->
                <div class="row stats-cards g-3">
                    <div class="col-4">
                        <div class="stat-card p-3 rounded-4 bg-dark bg-opacity-50 text-center">
                            <h3 class="mb-0 fw-bold counter">100,000+</h3>
                            <p class="mb-0 small">Happy Travelers</p>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-card p-3 rounded-4 bg-dark bg-opacity-50 text-center">
                            <h3 class="mb-0 fw-bold counter">10,000+</h3>
                            <p class="mb-0 small">Visas Processed</p>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-card p-3 rounded-4 bg-dark bg-opacity-50 text-center">
                            <h3 class="mb-0 fw-bold counter">99.5%</h3>
                            <p class="mb-0 small">Success Rate</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6" data-aos="fade-left">
                <div class="position-relative hero-image-container">
                    <div class="hero-image-glow"></div>
                    <div class="hero-card-container">
                        <div class="hero-card bg-white p-4 rounded-4 shadow-lg">
                            <div class="d-flex align-items-center mb-3">
                                <div class="hero-card-icon bg-warning rounded-circle p-2 me-3">
                                    <i class="bi bi-airplane-fill text-dark"></i>
                                </div>
                                <h4 class="mb-0 text-dark">Ready to Travel?</h4>
                            </div>
                            <p class="text-muted mb-3">Get your visa approved in as little as 24 hours</p>
                            <a href="#" class="btn btn-warning rounded-pill w-100 hover-scale">Apply Now</a>
                        </div>
                    </div>
                    <img src="assets/images/fly-high.webp" alt="Travel Visa" class="img-fluid rounded-4 hero-main-image">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mobile Hero Section - Visible only on smaller screens -->
<section class="container-fluid hero-section-mobile text-white d-block d-lg-none" id="heroSectionMobile">
    <!-- Floating Background Shapes -->
    <div class="vs25FloatingShape vs25Shape1"></div>
    <div class="vs25FloatingShape vs25Shape2"></div>
    <div class="vs25FloatingShape vs25Shape3"></div>

    <div class="container h-100 position-relative z-1">
        <div class="row h-100 py-4">
            <!-- Mobile Header -->
            <div class="col-12 text-center mb-4" data-aos="fade-down">
                <h1 class="vs25DisplayHeading">Visa <span class="text-warning">Simplified</span></h1>
                <p class="vs25Subheading mb-3">Seamless, stress-free visa processing with expert guidance</p>
            </div>

            <!-- Mobile Image -->
            <div class="col-12 mb-4" data-aos="zoom-in">
                <div class="mobile-image-wrapper position-relative">
                    <div class="hero-image-glow"></div>
                    <img src="assets/images/fly-high.webp" alt="Travel Visa" class="img-fluid rounded-4 mobile-hero-image shadow-lg">
                    <div class="position-absolute top-0 end-0 mt-3 me-3">
                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold shadow-sm">Fast Approval</span>
                    </div>
                </div>
            </div>

            <!-- Mobile Search -->
            <div class="col-12 mb-4" data-aos="fade-up">
                <div class="mobile-search-container">
                    <form action="search" method="get" id="searchFormMobile" autocapitalize="off" autocomplete="off" autocorrect="off" spellcheck="false">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-geo-alt-fill text-primary"></i>
                            </span>
                            <input type="text" id="searchDestinationMobile" class="form-control border-start-0 py-3" name="q" placeholder="Search a Country" required>
                            <button class="btn btn-primary search-btn-mobile" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                        <label for="searchDestinationMobile" class="form-label text-white mt-2 ms-2">Where to?</label>
                    </form>
                </div>
            </div>

            <!-- Mobile Popular Destinations -->
            <div class="col-12 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="mobile-destinations-wrapper">
                    <div class="mobile-destinations-scroll py-2">
                        <a class="mobile-destination-pill" href="country/apply-for-singapore-visa-online">
                            <i class="bi bi-airplane-fill me-2"></i>
                            <span>Singapore</span>
                        </a>
                        <a class="mobile-destination-pill" href="country/apply-for-united-arab-emirates-visa-online">
                            <i class="bi bi-airplane-fill me-2"></i>
                            <span>UAE</span>
                        </a>
                        <a class="mobile-destination-pill" href="country/apply-for-schengen-visa-online">
                            <i class="bi bi-airplane-fill me-2"></i>
                            <span>Schengen</span>
                        </a>
                        <a class="mobile-destination-pill" href="#">
                            <i class="bi bi-airplane-fill me-2"></i>
                            <span>Malaysia</span>
                        </a>
                        <a class="mobile-destination-pill" href="#">
                            <i class="bi bi-airplane-fill me-2"></i>
                            <span>Thailand</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Mobile Stats -->
            <div class="col-12 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="row g-3">
                    <div class="col-4">
                        <div class="mobile-stat-card">
                            <div class="mobile-stat-number">100K+</div>
                            <div class="mobile-stat-title">Happy Travelers</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mobile-stat-card">
                            <div class="mobile-stat-number">10K+</div>
                            <div class="mobile-stat-title">Visas Processed</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mobile-stat-card">
                            <div class="mobile-stat-number">99.5%</div>
                            <div class="mobile-stat-title">Success Rate</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile CTA Button -->
            <div class="col-12 text-center mt-4" data-aos="fade-up" data-aos-delay="300">
                <a href="#" class="btn btn-warning btn-lg rounded-pill fw-bold mobile-cta-button shadow-lg">
                    <i class="bi bi-cursor-fill me-2"></i>Apply for Visa Now
                </a>
            </div>
        </div>
    </div>
</section>

<style>
    /* Common Styles */
    .hero-section,
    .hero-section-mobile {
        min-height: 100vh;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #1a2a6c, #b21f1f, #fdbb2d);
    }

    /* Desktop Hero Styles */
    .hero-section {
        padding: 120px 0 80px;
    }

    .vs25FloatingShape {
        position: absolute;
        border-radius: 50%;
        opacity: 0.1;
        background: white;
        z-index: 0;
    }

    .vs25Shape1 {
        width: 300px;
        height: 300px;
        top: -100px;
        right: -50px;
        animation: float 15s ease-in-out infinite;
    }

    .vs25Shape2 {
        width: 200px;
        height: 200px;
        bottom: -50px;
        left: -50px;
        animation: float 20s ease-in-out infinite reverse;
    }

    .vs25Shape3 {
        width: 150px;
        height: 150px;
        top: 40%;
        right: -30px;
        animation: float 18s ease-in-out infinite;
    }

    .search-glass-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 20px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .search-glass-card:hover {
        box-shadow: 0 12px 48px rgba(0, 0, 0, 0.15);
        transform: translateY(-5px);
    }

    .search-icon {
        top: 50%;
        left: 20px;
        transform: translateY(-50%);
        color: #6c757d;
        z-index: 10;
    }

    .search-btn {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .search-btn:hover {
        background-color: #e0a800;
        transform: translateY(-50%) scale(1.05);
    }

    .hero-image-container {
        position: relative;
        z-index: 1;
    }

    .hero-image-glow {
        position: absolute;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at center, rgba(255, 193, 7, 0.3) 0%, transparent 70%);
        z-index: -1;
        animation: pulse 3s infinite alternate;
    }

    .hero-main-image {
        border: 5px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        transition: all 0.5s ease;
    }

    .hero-main-image:hover {
        transform: scale(1.02);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
    }

    .hero-card-container {
        position: absolute;
        bottom: -30px;
        right: -30px;
        width: 250px;
        z-index: 2;
    }

    .hero-card {
        transform: rotate(5deg);
        transition: all 0.3s ease;
        animation: bounce 3s infinite alternate;
    }

    .hero-card:hover {
        transform: rotate(0deg) scale(1.05);
        animation: none;
    }

    .hero-card-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-card {
        transition: all 0.3s ease;
        animation: fadeIn 0.8s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .counter {
        animation: fadeIn 1s;
    }

    .trending-destinations a {
        transition: all 0.3s ease;
    }

    .trending-destinations a:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .hover-scale {
        transition: all 0.3s ease;
    }

    .hover-scale:hover {
        transform: scale(1.05);
    }

    /* Mobile Hero Styles */
    .hero-section-mobile {
        padding: 80px 0 40px;
        background: linear-gradient(135deg, #1a2a6c, #b21f1f, #fdbb2d);
    }

    /* Mobile Header */
    .vs25DisplayHeading {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .vs25Subheading {
        font-size: 1rem;
        opacity: 0.9;
        max-width: 300px;
        margin: 0 auto;
    }

    /* Mobile Image */
    .mobile-image-wrapper {
        margin: 0 auto;
        max-width: 320px;
        border-radius: 16px;
        overflow: hidden;
    }

    .mobile-hero-image {
        border: 3px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
        transition: transform 0.3s ease;
    }

    /* Mobile Search */
    .mobile-search-container {
        max-width: 350px;
        margin: 0 auto;
    }

    .search-btn-mobile {
        border-top-right-radius: 30px;
        border-bottom-right-radius: 30px;
        padding: 0.5rem 1rem;
    }

    /* Mobile Destinations */
    .mobile-destinations-wrapper {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 5px;
        margin: 0 auto;
        max-width: 350px;
    }

    .mobile-destinations-scroll {
        display: flex;
        overflow-x: auto;
        padding: 5px;
        scrollbar-width: none;
        /* Firefox */
        -ms-overflow-style: none;
        /* IE and Edge */
    }

    .mobile-destinations-scroll::-webkit-scrollbar {
        display: none;
        /* Chrome, Safari, Opera */
    }

    .mobile-destination-pill {
        display: flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        text-decoration: none;
        padding: 8px 16px;
        border-radius: 30px;
        margin-right: 10px;
        white-space: nowrap;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .mobile-destination-pill:hover,
    .mobile-destination-pill:active {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
        color: white;
    }

    /* Mobile Stats */
    .mobile-stat-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(5px);
        border-radius: 12px;
        padding: 15px 10px;
        text-align: center;
        height: 100%;
        transition: all 0.3s ease;
    }

    .mobile-stat-card:hover {
        transform: translateY(-3px);
        background: rgba(255, 255, 255, 0.2);
    }

    .mobile-stat-number {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .mobile-stat-title {
        font-size: 0.75rem;
        opacity: 0.9;
    }

    /* Mobile CTA Button */
    .mobile-cta-button {
        padding: 12px 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }

    .mobile-cta-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
    }

    @media (max-width: 992px) {
        .hero-section {
            padding: 100px 0 60px;
        }

        .hero-card-container {
            position: relative;
            bottom: 0;
            right: 0;
            width: 100%;
            margin-top: 20px;
        }

        .hero-card {
            transform: none;
        }
    }

    @media (max-width: 768px) {
        .mobile-stat-item {
            width: calc(50% - 15px);
        }
    }

    @media (max-width: 576px) {
        .search-glass-card {
            padding: 15px;
        }

        .search-btn {
            width: 40px;
            height: 40px;
        }

        .stat-card {
            padding: 10px !important;
        }

        .stat-card h3 {
            font-size: 1.2rem;
        }

        .stat-card p {
            font-size: 0.7rem;
        }

        .mobile-stat-item {
            width: 100%;
        }

        .mobile-stat-text h3 {
            font-size: 1.1rem;
        }

        .mobile-stat-text p {
            font-size: 0.8rem;
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes bounce {

        0%,
        100% {
            transform: rotate(5deg) translateY(0);
        }

        50% {
            transform: rotate(5deg) translateY(-10px);
        }
    }

    @keyframes pulse {
        0% {
            opacity: 0.2;
        }

        100% {
            opacity: 0.4;
        }
    }
</style>


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
<!-- ./Read Reviews -->


<!-- Stats Section -->
<?php require 'components/stats.php'; ?>
<!-- ./Stats Section -->





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
            <!-- Visa types in a grid -->
            <div class="row g-4 mb-4">
                <div class="col-md-4 col-lg-2">
                    <div class="visaa-card">
                        <div class="cardd-body">
                            <div class="visaa-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-circle-fill" viewBox="0 0 16 16">
                                    <circle cx="8" cy="8" r="8" />
                                </svg>
                            </div>
                            <h class="visaa-title">30 days Tourist Visit</h>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-2">
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
                <div class="col-md-4 col-lg-2">
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
                <div class="col-md-4 col-lg-2">
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
                <div class="col-md-4 col-lg-2">
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
                <div class="col-md-4 col-lg-2">
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
<section class="container my-3">
    <div class="row">
        <div class="col-12">
            <div class="card p-3 rounded-4 border-0">
                <div class="card-body">
                    <div class="row align-items-center">
                        <!-- First Column: Text and Input Form -->
                        <div class="col-lg-7 mb-3">
                            <h2 class="fw-bold mb-2">Ready to get started? Enter your travel destination</h2>
                            <p class="text-muted fs-6 mb-3">🚀 Visa Process Made Easy<br>
                                📋 Get Your Checklist<br>
                                💥 Sign Up FREE!</p>
                            <div class="row justify-content-left">
                                <div class="col-lg-10">
                                    <form action="search" method="get" class="mb-3">
                                        <div class="input-group input-group-lg rounded-pill bg-white" style="border: 1px solid var(--blue);">
                                            <span class="input-group-text bg-white border-0 rounded-start-pill ps-3">
                                                <i class="bi bi-airplane-fill fs-4" style="color: var(--blue);"></i>
                                            </span>
                                            <input type="text" name="q" class="form-control border-0 shadow-none py-2" placeholder="Search a country..." required>
                                            <button class="btn btn-blue rounded-end-pill px-3">
                                                <i class="bi bi-search"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Second Column: Benefits Cards -->
                        <div class="col-lg-5">
                            <div class="row g-2">
                                <!-- First Card -->
                                <div class="col-md-6 col-lg-12">
                                    <div class="card bg-white border-0 rounded-4 shadow-sm h-100 hover-scale">
                                        <div class="card-body p-3 d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded-3 p-2 me-2">
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
                                        <div class="card-body p-3 d-flex align-items-center">
                                            <div class="bg-success bg-opacity-10 rounded-3 p-2 me-2">
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
                                        <div class="card-body p-3 d-flex align-items-center">
                                            <div class="bg-warning bg-opacity-10 rounded-3 p-2 me-2">
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
<?php if (!isset($_SESSION['user_id'])): ?>
    <div class="icon-container">
        <div class="icon" id="callbackIcon" data-bs-toggle="popover" data-bs-html="true" data-bs-content=' 
            <button class="btn-close float-end" onclick="hidePopover()"></button>
            <img src="assets/images/main-logo.png" alt="callback" class="img-fluid mx-auto d-block mb-2">
            <p>For a free & immediate callback, enter your number below and we will call you within 5 minutes.</p>
            <input type="tel" class="form-control form-control-lg" placeholder="Enter your number" id="mobile_home" name="mobile_home">
            <input type="hidden" name="country_code" id="country_code">
            <button id="submit_button" class="btn btn-blue rounded-pill mt-2" onclick="requestCallback()">Call Me</button>'>
            <div class="ripple"></div>
            <div class="ripple"></div>
            <div class="ripple"></div>
            <span><i class="bi bi-telephone-fill text-white"></i></span>
        </div>
    </div>
<?php endif; ?>

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
                        we’ve earned the trust of travellers worldwide.</p>

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
        document.querySelector('.nav-link[href*="how-it-works"]').addEventListener("click", function(event) {
            event.preventDefault(); // Prevent default anchor behavior

            const target = document.querySelector("#how-it-works"); // Select target section
            if (target) {
                window.scrollTo({
                    top: target.offsetTop - 50, // Adjust 50px for fixed headers if needed
                    behavior: "smooth"
                });
            }
        });
    });
</script>

<script>
    // JavaScript to show hero section after page load
    window.addEventListener('load', function() {
        document.getElementById('heroSection').style.display = 'block';
        document.getElementById('travelDestinationDiv').style.display = 'block';
    });
</script>

<!-- callback -->
<script>
    let popoverInstance;
    document.addEventListener("DOMContentLoaded", function() {
        const callbackIcon = document.getElementById('callbackIcon');

        if (callbackIcon) {
            popoverInstance = new bootstrap.Popover(callbackIcon, {
                trigger: 'manual', // Manual control
                sanitize: false,
                html: true,
                placement: 'left', // Adjust as needed
                container: 'body' // Keep it inside body to avoid breaking Bootstrap
            });

            // Show popover on click
            callbackIcon.addEventListener('click', function() {
                if (popoverInstance) {
                    popoverInstance.show();
                    updatePopoverPosition(); // Ensure correct positioning

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
            window.addEventListener('scroll', updatePopoverPosition, true); // 'true' ensures it fires early
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
            bootstrap.Popover.getInstance(callbackIcon)?.update();
        }
    }
</script>

<!-- faq -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const faqButtons = document.querySelectorAll(".faq-question");

        faqButtons.forEach(button => {
            const targetId = button.getAttribute("data-bs-target");
            const targetCollapse = document.querySelector(targetId);
            const icon = button.querySelector("i");

            // Listen for Bootstrap collapse events
            targetCollapse.addEventListener("show.bs.collapse", function() {
                // Close other open collapses
                faqButtons.forEach(otherButton => {
                    if (otherButton !== button) {
                        const otherTargetId = otherButton.getAttribute("data-bs-target");
                        const otherCollapse = document.querySelector(otherTargetId);
                        if (otherCollapse.classList.contains('show')) {
                            otherCollapse.classList.remove('show');
                            bootstrap.Collapse.getInstance(otherCollapse).hide();
                            const otherIcon = otherButton.querySelector("i");
                            otherIcon.classList.remove("bi-dash-circle");
                            otherIcon.classList.add("bi-plus-circle");
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
        const mobileNumber = document.getElementById("mobile_home").value.trim();
        const submitButton = document.getElementById("submit_button"); // Assuming the button has this ID

        if (!mobileNumber) {
            Notiflix.Notify.failure("Please enter your mobile number.");
            return;
        }

        // Validate mobile number format (basic validation)
        const phoneRegex = /^[0-9]{10,15}$/; // Adjust regex as needed for your requirements
        if (!phoneRegex.test(mobileNumber)) {
            Notiflix.Notify.failure("Please enter a valid mobile number.");
            return;
        }

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
                        countryCode: callingCode // Use the fetched country code here
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Re-enable the button and reset text
                    submitButton.disabled = false;
                    submitButton.textContent = "Call Me"; // Assuming the original text is "Submit"

                    if (data.status === "success") {
                        Notiflix.Notify.success(data.message || "Callback request successful.");
                        document.getElementById("mobile_home").value = ''; // Clear input field
                    } else {
                        Notiflix.Notify.failure(data.error || "Callback request failed. Please try again.");
                    }
                })
                .catch(error => {
                    // Re-enable the button and reset text
                    submitButton.disabled = false;
                    submitButton.textContent = "Call Me"; // Assuming the original text is "Submit"

                    Notiflix.Notify.failure("An error occurred. Please try again.");
                    console.error("Error:", error);
                });
        });
    }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
    // Initialize AOS
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({
            duration: 800,
            once: false,
            easing: 'ease-out-cubic'
        });

        // Intersection Observer for Counter Animation
        const counters = document.querySelectorAll('.number-count');
        const circle = document.querySelector('.circle-fill');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Animate progress circle
                    if (circle) {
                        setTimeout(() => {
                            circle.style.strokeDashoffset = '1.4'; // 99.5% of 283
                        }, 300);
                    }

                    // Animate counters
                    counters.forEach(counter => {
                        counter.classList.add('visible');
                        const target = +counter.getAttribute('data-target');
                        const duration = 2000; // 2 seconds
                        const increment = target / (duration / 16); // 60fps

                        let currentCount = 0;
                        const updateCount = () => {
                            if (currentCount < target) {
                                currentCount += increment;
                                counter.textContent = Math.min(Math.floor(currentCount), target);
                                requestAnimationFrame(updateCount);
                            } else {
                                counter.textContent = target;
                            }
                        };

                        setTimeout(() => {
                            updateCount();
                        }, 500);
                    });
                }
            });
        }, {
            threshold: 0.5
        });

        // Observe the statistics area
        observer.observe(document.querySelector('.statistics-area'));

        // Make floating shapes more random
        const floaters = document.querySelectorAll('.floating-shape');
        floaters.forEach((floater, index) => {
            floater.style.animationDuration = `${Math.random() * 5 + 8}s`;
            floater.style.animationDelay = `${Math.random() * 2}s`;
        });
    });
</script>


</body>

</html>