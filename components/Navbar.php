<?php
$pages = $database->select('pages', '*', [
    'pagePosition' => 'header',
    'is_active' => 1
]);
?>
<!-- Desktop Navbar -->
<nav class="navbar navbar-expand-lg fixed-top py-2 d-none d-lg-block modern-navbar">
    <div class="container">
        <a class="navbar-brand" href="https://fayyaztravels.com/visa">
            <img src="assets/images/main-logo.png" alt="Fayyaz Travels" height="42px" id="navbar-logo">
        </a>

        <!-- Nav links -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="https://fayyaztravels.com/visa/">Home</a>
                </li>
                <?php foreach ($pages as $page): ?>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="https://fayyaztravels.com/visa/pages/<?= $page['pageSlug']; ?>"><?= $page['pageName']; ?></a>
                    </li>
                <?php endforeach; ?>
                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="https://fayyaztravels.com/visa/home#how-it-works">How it Works?</a>
                </li>
            </ul>
        </div>

        <div class="navbar-actions d-flex align-items-center">
            <!-- Search icon -->
            <div class="search-toggle me-3">
                <i class="bi bi-search fs-5 cursor-pointer search-icon" id="searchToggle"></i>
            </div>

            <!-- Country indicator with emoji -->
            <span class="me-3 countryEmoji"></span>

            <!-- Button group -->
            <div class="d-flex me-3">
                <a target="_blank" href="https://api.whatsapp.com/send/?phone=6594314389&text=Hi!%20I%20am%20interested%20in%20visa%20processing&type=phone_number&app_absent=0" class="btn btn-outline-success btn-sm rounded-pill me-2 d-flex align-items-center">
                    <i class="bi bi-whatsapp me-2"></i> Chat
                </a>
                <a href="https://fayyaztravels.com/visa/auth/login" class="btn btn-outline-blue btn-sm rounded-pill d-flex align-items-center">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Log in
                </a>
            </div>

            <!-- Contact section -->
            <div class="d-flex flex-column contact-info">
                <small class="text-dark fw-medium">Call Visa Experts</small>
                <a href="tel:+6562352900" class="text-decoration-none d-flex align-items-center">
                    <i class="bi bi-telephone-fill text-blue me-2"></i>
                    <span class="fw-bold">(+65) 6235 2900</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Expandable Search Bar -->
    <div class="search-overlay" id="searchOverlay">
        <div class="container">
            <button type="button" class="btn-close position-absolute top-0 end-0 m-3" id="closeSearch"></button>
            <form action="https://fayyaztravels.com/visa/search" method="get" class="search-form">
                <div class="input-group">
                    <input type="text" class="form-control form-control-lg"
                        placeholder="Where do you want to travel?" name="q" id="navbarSearch">
                    <button class="btn btn-blue" type="submit">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>
</nav>

<!-- Mobile Navbar -->
<nav class="navbar fixed-top py-3 d-block d-lg-none mobile-navbar">
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between w-100 position-relative">
            <div class="d-flex align-items-center" style="width: 80px;">
                <button class="navbar-toggler border-0 d-flex align-items-center justify-content-center" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar">
                    <i class="bi bi-list fs-4"></i>
                </button>
            </div>

            <a class="navbar-brand position-absolute start-50 translate-middle-x" href="https://fayyaztravels.com/visa">
                <img src="assets/images/main-logo.png" alt="Fayyaz Travels" height="42px">
            </a>

            <div class="d-flex align-items-center" style="width: 80px; justify-content: flex-end;">
                <button class="btn btn-link p-0 me-3 d-flex align-items-center justify-content-center" id="mobileSearchToggle">
                    <i class="bi bi-search fs-5 text-dark"></i>
                </button>
                <a target="_blank" href="https://api.whatsapp.com/send/?phone=6594314389&text=Hi!%20I%20am%20interested%20in%20visa%20processing&type=phone_number&app_absent=0" class="p-0 d-flex align-items-center justify-content-center">
                    <i class="bi bi-whatsapp fs-5 text-success"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Mobile Search Overlay -->
    <div class="search-overlay" id="mobileSearchOverlay">
        <div class="container">
            <button type="button" class="btn-close position-absolute top-0 end-0 m-3" id="closeMobileSearch"></button>
            <form action="https://fayyaztravels.com/visa/search" method="get" class="search-form">
                <div class="input-group">
                    <input type="text" class="form-control form-control-lg"
                        placeholder="Search destination" name="q">
                    <button class="btn btn-blue" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</nav>

<!-- Offcanvas Menu -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">
            <img src="assets/images/main-logo.png" alt="Fayyaz Travels" height="38px">
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
        <!-- Country indicator -->
        <div class="mb-3 countryEmoji"></div>

        <ul class="navbar-nav mb-4">
            <li class="nav-item">
                <a class="nav-link fw-semibold py-2" href="https://fayyaztravels.com/visa/">
                    <i class="bi bi-house-door me-2"></i>Home
                </a>
            </li>
            <?php foreach ($pages as $page): ?>
                <li class="nav-item">
                    <a class="nav-link fw-semibold py-2" href="https://fayyaztravels.com/visa/pages/<?= $page['pageSlug']; ?>">
                        <i class="bi bi-file-text me-2"></i><?= $page['pageName']; ?>
                    </a>
                </li>
            <?php endforeach; ?>
            <li class="nav-item">
                <a class="nav-link fw-semibold py-2" href="https://fayyaztravels.com/visa/home#how-it-works">
                    <i class="bi bi-info-circle me-2"></i>How it Works?
                </a>
            </li>
        </ul>

        <!-- Button group -->
        <div class="d-grid gap-2 mb-4">
            <a target="_blank" href="https://api.whatsapp.com/send/?phone=6594314389&text=Hi!%20I%20am%20interested%20in%20visa%20processing&type=phone_number&app_absent=0" class="btn btn-outline-blue rounded-pill" style="width: auto;">
                <i class="bi bi-whatsapp me-2"></i> Chat with Us
            </a>
            <a href="https://fayyaztravels.com/visa/auth/signup" class="btn btn-outline-blue rounded-pill" style="width: auto;">
                <i class="bi bi-person-plus me-2"></i> Sign Up
            </a>
            <a href="https://fayyaztravels.com/visa/auth/login" class="btn btn-outline-blue rounded-pill" style="width: auto;">
                <i class="bi bi-box-arrow-in-right me-2"></i> Log in
            </a>
        </div>

        <!-- Contact info at the bottom -->
        <div class="mt-auto border-top pt-3">
            <small class="text-secondary fw-medium d-block mb-1">Call our visa experts</small>
            <a href="tel:+6562352900" class="text-decoration-none d-flex align-items-center">
                <i class="bi bi-telephone-fill me-2 fs-5" style="color: var(--blue);"></i>
                <span class="fw-bold fs-5" style="color: var(--blue);">(+65) 6235 2900</span>
            </a>
        </div>
    </div>
</div>

<!-- Add CSS for modern navbar styling -->
<style>
    /* Modern Navbar Styling */
    .modern-navbar {
        background-color: #ffffff;
        border-bottom: 1px solid #e9ecef;
        transition: all 0.3s ease;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
    }

    .mobile-navbar {
        background-color: #ffffff;
        border-bottom: 1px solid #e9ecef;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
        min-height: 70px;
    }

    .modern-navbar.scrolled {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }

    .modern-navbar .navbar-brand {
        z-index: 1031;
    }

    .modern-navbar .nav-link {
        color: #343a40;
        font-weight: 500;
        padding: 0.5rem 1rem;
        margin: 0 0.2rem;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .modern-navbar .nav-link:hover {
        color: var(--golden);
        background-color: rgba(175, 135, 0, 0.08);
        transform: translateY(-2px);
    }

    .modern-navbar .nav-link.active {
        color: var(--golden);
        background-color: rgba(175, 135, 0, 0.12);
        box-shadow: 0 0 10px rgba(175, 135, 0, 0.1);
    }

    #navbar-logo {
        transition: height 0.3s ease;
    }

    .btn-golden {
        background-color: var(--golden);
        border-color: var(--golden);
        color: white;
        transition: all 0.3s ease;
    }

    .btn-brown {
        background-color: var(--brown);
        border-color: var(--brown);
        color: white;
        transition: all 0.3s ease;
    }

    .btn-golden:hover,
    .btn-brown:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        background-color: var(--golden-dark);
        border-color: var(--golden-dark);
    }

    .btn-brown:hover {
        background-color: var(--brown-dark);
        border-color: var(--brown-dark);
    }

    .cursor-pointer {
        cursor: pointer;
    }

    /* Search Icon Styling */
    .search-icon {
        color: #343a40;
        transition: all 0.3s ease;
        position: relative;
        cursor: pointer;
    }

    .search-icon:hover {
        color: var(--golden);
        transform: scale(1.1);
    }

    /* Search Overlay */
    .search-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 0;
        background-color: rgba(255, 255, 255, 0.98);
        z-index: 1040;
        overflow: hidden;
        transition: height 0.4s ease;
        display: flex;
        align-items: center;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.15);
    }

    .search-overlay.active {
        height: 100vh;
    }

    .search-form input {
        font-size: 1.25rem;
        height: 60px;
        border: 2px solid #e9ecef;
        border-radius: 10px 0 0 10px;
        transition: all 0.3s ease;
    }

    .search-form input:focus {
        box-shadow: 0 0 15px var(--blue-dark);
        border-color: var(--blue);
    }

    .search-form .btn-golden {
        border-radius: 0 10px 10px 0;
    }

    /* Offcanvas styling */
    .offcanvas {
        border-right: 1px solid #e9ecef;
    }

    .offcanvas-body .nav-link {
        color: #343a40;
        border-radius: 8px;
        transition: all 0.3s ease;
        margin-bottom: 5px;
    }

    .offcanvas-body .nav-link:hover {
        background-color: rgba(175, 135, 0, 0.08);
        color: var(--golden);
        transform: translateX(5px);
    }

    /* Contact info styling */
    .contact-info {
        border-left: 1px solid #e9ecef;
        padding-left: 15px;
        transition: all 0.3s ease;
    }

    .contact-info:hover a {
        transform: translateY(-2px);
    }

    .contact-info a {
        color: #343a40;
        transition: all 0.3s ease;
    }

    .contact-info a:hover {
        color: var(--golden);
    }

    @media (max-width: 992px) {
        .mobile-navbar {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }

        .mobile-navbar .btn-link {
            color: #343a40;
            text-decoration: none;
            padding: 0.5rem;
        }

        .mobile-navbar .btn-link:hover {
            color: var(--golden);
        }

        .mobile-navbar .navbar-brand img {
            height: 42px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Navbar scroll effect
        const navbar = document.querySelector('.modern-navbar');
        const navbarLogo = document.getElementById('navbar-logo');

        if (navbar) {
            window.addEventListener('scroll', function() {
                if (window.scrollY > 10) {
                    navbar.classList.add('scrolled');
                    if (navbarLogo) {
                        navbarLogo.style.height = '36px';
                    }
                } else {
                    navbar.classList.remove('scrolled');
                    if (navbarLogo) {
                        navbarLogo.style.height = '42px';
                    }
                }
            });
        }

        // Desktop search toggle
        const searchToggle = document.getElementById('searchToggle');
        const searchOverlay = document.getElementById('searchOverlay');
        const closeSearch = document.getElementById('closeSearch');
        const navbarSearch = document.getElementById('navbarSearch');

        if (searchToggle && searchOverlay && closeSearch) {
            searchToggle.addEventListener('click', function(e) {
                e.preventDefault();
                searchOverlay.classList.add('active');
                if (navbarSearch) {
                    setTimeout(() => {
                        navbarSearch.focus();
                    }, 300);
                }
            });

            closeSearch.addEventListener('click', function() {
                searchOverlay.classList.remove('active');
            });
        }

        // Mobile search toggle
        const mobileSearchToggle = document.getElementById('mobileSearchToggle');
        const mobileSearchOverlay = document.getElementById('mobileSearchOverlay');
        const closeMobileSearch = document.getElementById('closeMobileSearch');
        const mobileSearchInput = mobileSearchOverlay?.querySelector('input[name="q"]');

        if (mobileSearchToggle && mobileSearchOverlay && closeMobileSearch) {
            mobileSearchToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation(); // Stop event bubbling
                mobileSearchOverlay.classList.add('active');
                if (mobileSearchInput) {
                    setTimeout(() => {
                        mobileSearchInput.focus();
                    }, 300);
                }
            });

            closeMobileSearch.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation(); // Stop event bubbling
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

        // Close search when clicking outside
        document.addEventListener('click', function(e) {
            if (searchOverlay && searchOverlay.classList.contains('active')) {
                if (!searchOverlay.contains(e.target) && e.target !== searchToggle) {
                    searchOverlay.classList.remove('active');
                }
            }
            if (mobileSearchOverlay && mobileSearchOverlay.classList.contains('active')) {
                if (!mobileSearchOverlay.contains(e.target) && e.target !== mobileSearchToggle && !mobileSearchToggle.contains(e.target)) {
                    mobileSearchOverlay.classList.remove('active');
                }
            }
        });
    });
</script>