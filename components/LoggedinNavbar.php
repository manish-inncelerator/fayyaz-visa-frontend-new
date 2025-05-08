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
                <div class="dropdown">
                    <button class="btn btn-outline-blue btn-sm rounded-pill me-2 d-flex align-items-center position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell me-2"></i> Notifications
                        <span class="notification-indicator"></span>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge" style="display: none;">
                            0
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationDropdown">
                        <li class="dropdown-header d-flex justify-content-between align-items-center">
                            <span>Notifications</span>
                            <button class="btn btn-link btn-sm text-decoration-none p-0" id="markAllRead">Mark all as read</button>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <div class="notification-list" style="max-height: 300px; overflow-y: auto;">
                            <!-- Notifications will be dynamically added here -->
                            <div class="text-center py-3 text-muted no-notifications">
                                No new notifications
                            </div>
                        </div>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li class="px-2 pb-2"><a class="dropdown-item text-center rounded-pill w-100" href="https://fayyaztravels.com/visa/notifications">View all notifications</a></li>
                    </ul>
                </div>
                <a href="https://fayyaztravels.com/visa/applications" class="btn btn-outline-blue btn-sm rounded-pill d-flex align-items-center">
                    <i class="bi bi-file-richtext me-2"></i> Applications
                </a>
            </div>

            <!-- Profile Dropdown -->
            <div class="dropdown me-3">
                <button class="btn btn-white" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="assets/images/dp/no-user.jpg" alt="Profile" class="rounded-circle" width="36" height="36">
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item" href="https://fayyaztravels.com/visa/settings">Settings</a></li>
                    <li><a class="dropdown-item" href="https://fayyaztravels.com/visa/logout">Logout</a></li>
                </ul>
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
                <button class="btn btn-link p-0 me-3 d-flex align-items-center justify-content-center position-relative" id="mobileNotificationToggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-bell fs-5 text-dark"></i>
                    <span class="notification-indicator"></span>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge-mobile" style="display: none;">
                        0
                    </span>
                </button>
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

    <!-- Mobile Notification Dropdown -->
    <div class="dropdown-menu notification-dropdown" id="mobileNotificationDropdown">
        <div class="dropdown-header d-flex justify-content-between align-items-center">
            <span>Notifications</span>
            <button class="btn btn-link btn-sm text-decoration-none p-0" id="markAllReadMobile">Mark all as read</button>
        </div>
        <hr class="dropdown-divider">
        <div class="notification-list" style="max-height: 300px; overflow-y: auto;">
            <!-- Notifications will be dynamically added here -->
            <div class="text-center py-3 text-muted no-notifications">
                No new notifications
            </div>
        </div>
        <hr class="dropdown-divider">
        <a class="dropdown-item text-center" href="https://fayyaztravels.com/visa/notifications">View all notifications</a>
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

        <!-- User Profile Section -->
        <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
            <img src="assets/images/dp/no-user.jpg" alt="Profile" class="rounded-circle me-3" width="50" height="50">
            <div>
                <h6 class="mb-0 fw-bold">My Account</h6>
                <div class="d-flex mt-2">
                    <a href="https://fayyaztravels.com/visa/settings" class="btn btn-sm btn-outline-secondary me-2">
                        <i class="bi bi-gear-fill me-1"></i> Settings
                    </a>
                    <a href="https://fayyaztravels.com/visa/logout" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </a>
                </div>
            </div>
        </div>

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
            <a href="https://fayyaztravels.com/visa/applications" class="btn btn-outline-blue rounded-pill" style="width: auto;">
                <i class="bi bi-file-richtext me-2"></i> My Applications
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
        color: var(--blue);
        background-color: rgba(0, 123, 255, 0.08);
        transform: translateY(-2px);
    }

    .modern-navbar .nav-link.active {
        color: var(--blue);
        background-color: rgba(0, 123, 255, 0.12);
        box-shadow: 0 0 10px rgba(0, 123, 255, 0.1);
    }

    #navbar-logo {
        transition: height 0.3s ease;
    }

    .btn-blue {
        background-color: var(--blue);
        border-color: var(--blue);
        color: white;
        transition: all 0.3s ease;
    }

    .btn-blue:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        background-color: var(--blue-dark);
        border-color: var(--blue-dark);
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
        color: var(--blue);
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

    .search-form .btn-blue {
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
        background-color: rgba(0, 123, 255, 0.08);
        color: var(--blue);
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
        color: var(--blue);
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
            color: var(--blue);
        }

        .mobile-navbar .navbar-brand img {
            height: 42px;
        }
    }

    /* Notification Styling */
    .notification-dropdown {
        width: 320px;
        padding: 0;
        border: none;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        margin-top: 10px;
    }

    .notification-dropdown .dropdown-header {
        padding: 16px;
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        border-radius: 12px 12px 0 0;
    }

    .notification-dropdown .dropdown-header button {
        color: var(--blue);
        font-size: 0.85rem;
        font-weight: 500;
    }

    .notification-dropdown .dropdown-header button:hover {
        color: var(--blue-dark);
    }

    .notification-dropdown .dropdown-item {
        padding: 12px 16px;
        border-bottom: 1px solid #e9ecef;
        white-space: normal;
        transition: all 0.2s ease;
    }

    .notification-dropdown .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    .notification-dropdown .notification-item {
        display: flex;
        align-items: start;
        gap: 12px;
        padding: 16px;
        border-bottom: 1px solid #e9ecef;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .notification-dropdown .notification-item:hover {
        background-color: #f8f9fa;
    }

    .notification-dropdown .notification-item.unread {
        background-color: #e8f4ff;
    }

    .notification-dropdown .notification-item.unread:hover {
        background-color: #d8ecff;
    }

    .notification-dropdown .notification-item .notification-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        flex-shrink: 0;
        transition: background-color 0.2s ease;
    }

    .notification-dropdown .notification-item:hover .notification-icon {
        background-color: #e9ecef;
    }

    .notification-dropdown .notification-item .notification-content {
        flex: 1;
    }

    .notification-dropdown .notification-item .notification-title {
        font-weight: 600;
        margin-bottom: 4px;
        color: #2c3e50;
    }

    .notification-dropdown .notification-item .notification-message {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 4px;
    }

    .notification-dropdown .notification-item .notification-time {
        font-size: 0.8rem;
        color: #95a5a6;
    }

    .notification-dropdown .notification-item .notification-actions {
        display: flex;
        gap: 8px;
        margin-top: 8px;
    }

    .notification-dropdown .notification-item .notification-actions button {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
        transition: all 0.2s ease;
    }

    .notification-dropdown .notification-item .notification-actions button:hover {
        transform: translateY(-1px);
    }

    .notification-dropdown .notification-item .notification-actions button.btn-outline-primary:hover {
        background-color: var(--blue);
        border-color: var(--blue);
        color: white;
    }

    .notification-dropdown .notification-item .notification-actions button.btn-outline-danger:hover {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }

    .notification-badge,
    .notification-badge-mobile {
        font-size: 0.7rem;
        padding: 0.25em 0.6em;
        border: 2px solid #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .notification-badge-mobile {
        transform: translate(-50%, -50%) !important;
    }

    /* Mobile notification dropdown specific styles */
    @media (max-width: 992px) {
        .notification-dropdown {
            position: fixed !important;
            top: 60px !important;
            left: 0 !important;
            right: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            border-radius: 0 !important;
            max-height: calc(100vh - 60px);
            overflow-y: auto;
        }

        .notification-dropdown .dropdown-header {
            border-radius: 0;
            position: sticky;
            top: 0;
            z-index: 1;
            background-color: #fff;
        }

        .notification-dropdown .notification-item {
            padding: 16px;
        }

        .notification-dropdown .notification-item .notification-icon {
            width: 48px;
            height: 48px;
        }
    }

    .notification-dropdown .dropdown-item.rounded-pill {
        background-color: #f8f9fa;
        transition: all 0.2s ease;
        padding: 0.5rem 1rem;
    }

    .notification-dropdown .dropdown-item.rounded-pill:hover {
        background-color: var(--blue);
        color: white;
    }

    /* Notification Indicator Styles */
    .notification-indicator {
        position: absolute;
        top: 0;
        right: 0;
        width: 8px;
        height: 8px;
        background-color: #dc3545;
        border-radius: 50%;
        border: 2px solid #fff;
        display: none;
    }

    .notification-indicator.active {
        display: block;
    }
</style>

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

        // Close search on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (searchOverlay) searchOverlay.classList.remove('active');
                if (mobileSearchOverlay) mobileSearchOverlay.classList.remove('active');
            }
        });

        // Notifications functionality
        const notificationDropdown = document.getElementById('notificationDropdown');
        const mobileNotificationDropdown = document.getElementById('mobileNotificationDropdown');
        const notificationBadge = document.querySelector('.notification-badge');
        const notificationBadgeMobile = document.querySelector('.notification-badge-mobile');
        const notificationList = document.querySelector('.notification-list');
        const markAllReadBtn = document.getElementById('markAllRead');
        const markAllReadMobileBtn = document.getElementById('markAllReadMobile');
        const noNotifications = document.querySelector('.no-notifications');
        const mobileNotificationToggle = document.getElementById('mobileNotificationToggle');

        let notifications = [];

        // Fetch notifications from API
        async function fetchNotifications() {
            try {
                const userId = '<?php echo $_SESSION["user_id"] ?? ""; ?>';
                if (!userId) return;

                const response = await fetch(`api/v1/getNotification.php?user_id=${userId}`, {
                    headers: {
                        'HU': '<?= $hu; ?>'
                    }
                });
                const data = await response.json();

                if (data.success) {
                    notifications = data.notifications.map(notification => ({
                        id: notification.id,
                        message: notification.notification_message,
                        time: notification.created_at,
                        unread: notification.is_seen === 0,
                        icon: getNotificationIcon(notification.type)
                    }));

                    updateNotificationBadge();
                    renderNotifications();
                }
            } catch (error) {
                console.error('Error fetching notifications:', error);
            }
        }

        // Get appropriate icon based on notification type
        function getNotificationIcon(type) {
            const icons = {
                'application': 'bi-file-richtext text-primary',
                'payment': 'bi-credit-card-fill text-success',
                'document': 'bi-file-earmark-text text-info',
                'approval': 'bi-check-circle-fill text-success',
                'rejection': 'bi-x-circle-fill text-danger',
                'default': 'bi-bell-fill text-primary'
            };
            return icons[type] || icons.default;
        }

        function updateNotificationBadge() {
            const unreadCount = notifications.filter(n => n.unread).length;
            const notificationIndicators = document.querySelectorAll('.notification-indicator');

            if (unreadCount > 0) {
                notificationBadge.style.display = 'block';
                notificationBadgeMobile.style.display = 'block';
                notificationBadge.textContent = unreadCount;
                notificationBadgeMobile.textContent = unreadCount;
                notificationIndicators.forEach(indicator => {
                    indicator.classList.add('active');
                });
            } else {
                notificationBadge.style.display = 'none';
                notificationBadgeMobile.style.display = 'none';
                notificationIndicators.forEach(indicator => {
                    indicator.classList.remove('active');
                });
            }
        }

        function renderNotifications() {
            if (notifications.length === 0) {
                noNotifications.style.display = 'block';
                return;
            }

            noNotifications.style.display = 'none';
            const notificationHTML = notifications.map(notification => `
                <div class="notification-item ${notification.unread ? 'unread' : ''}" data-id="${notification.id}">
                    <div class="notification-icon">
                        <i class="bi ${notification.icon} fs-5"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-message">${notification.message}</div>
                        <div class="notification-time">${formatTime(notification.time)}</div>
                        <div class="notification-actions">
                            ${notification.unread ? `
                                <button class="btn btn-sm btn-outline-primary mark-read-link" data-id="${notification.id}">
                                    <i class="bi bi-check2 me-1"></i>Mark as read
                                </button>
                            ` : ''}
                            <button class="btn btn-sm btn-outline-danger delete-link" data-id="${notification.id}">
                                <i class="bi bi-trash me-1"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');

            // Update both desktop and mobile notification lists
            const notificationLists = document.querySelectorAll('.notification-list');
            notificationLists.forEach(list => {
                list.innerHTML = notificationHTML;
            });

            // Add click event to mark as read links
            document.querySelectorAll('.mark-read-link').forEach(link => {
                link.addEventListener('click', async (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    const id = parseInt(link.dataset.id);
                    await markNotificationAsRead(id);
                });
            });

            // Add click event to delete links
            document.querySelectorAll('.delete-link').forEach(link => {
                link.addEventListener('click', async (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    const id = parseInt(link.dataset.id);
                    await deleteNotification(id);
                });
            });
        }

        // Mark single notification as read
        async function markNotificationAsRead(id) {
            try {
                const response = await fetch('api/v1/markNotificationRead.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'HU': '<?= $hu; ?>'
                    },
                    body: JSON.stringify({
                        notification_id: id
                    })
                });
                const data = await response.json();
                if (data.success) {
                    // Close the dropdown
                    const dropdownElement = document.querySelector('.notification-dropdown');
                    const mobileDropdownElement = document.getElementById('mobileNotificationDropdown');
                    const dropdown = bootstrap.Dropdown.getInstance(dropdownElement);
                    const mobileDropdown = bootstrap.Dropdown.getInstance(mobileDropdownElement);

                    if (dropdown) dropdown.hide();
                    if (mobileDropdown) mobileDropdown.hide();

                    // Refresh notifications
                    await fetchNotifications();

                    // Reopen the dropdown after a short delay
                    setTimeout(() => {
                        if (dropdown) dropdown.show();
                        if (mobileDropdown) mobileDropdown.show();
                    }, 100);
                } else {
                    console.error('Error marking notification as read:', data.message);
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        }

        // Delete notification
        async function deleteNotification(id) {
            try {
                const response = await fetch('api/v1/deleteNotification', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'HU': '<?= $hu; ?>'
                    },
                    body: JSON.stringify({
                        notification_id: id
                    })
                });
                const data = await response.json();
                if (data.success) {
                    // Close the dropdown first
                    const dropdownElement = document.querySelector('.notification-dropdown');
                    const mobileDropdownElement = document.getElementById('mobileNotificationDropdown');
                    const dropdown = bootstrap.Dropdown.getInstance(dropdownElement);
                    const mobileDropdown = bootstrap.Dropdown.getInstance(mobileDropdownElement);

                    if (dropdown) dropdown.hide();
                    if (mobileDropdown) mobileDropdown.hide();

                    // Clear existing notifications
                    notifications = [];
                    const notificationLists = document.querySelectorAll('.notification-list');
                    notificationLists.forEach(list => {
                        list.innerHTML = '<div class="text-center py-3 text-muted no-notifications">No new notifications</div>';
                    });

                    // Update badge
                    updateNotificationBadge();

                    // Fetch fresh notifications
                    await fetchNotifications();

                    // Reopen the dropdown after a short delay
                    setTimeout(() => {
                        if (dropdown) dropdown.show();
                        if (mobileDropdown) mobileDropdown.show();
                    }, 100);
                }
            } catch (error) {
                console.error('Error deleting notification:', error);
            }
        }

        // Format time to show date only
        function formatTime(timestamp) {
            const date = new Date(timestamp);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }

        // Mark all as read
        async function markAllAsRead() {
            try {
                const userId = '<?php echo $_SESSION["user_id"] ?? ""; ?>';
                if (!userId) return;

                const response = await fetch(`api/v1/markNotifsRead.php?user_id=${userId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'HU': '<?= $hu; ?>'
                    }
                });
                const data = await response.json();
                if (data.success) {
                    notifications.forEach(n => n.unread = false);
                    document.querySelectorAll('.notification-item').forEach(item => {
                        item.classList.remove('unread');
                    });
                    updateNotificationBadge();
                }
            } catch (error) {
                console.error('Error marking all notifications as read:', error);
            }
        }

        if (markAllReadBtn) {
            markAllReadBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                markAllAsRead();
            });
        }

        if (markAllReadMobileBtn) {
            markAllReadMobileBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                markAllAsRead();
            });
        }

        // Mobile notification toggle
        if (mobileNotificationToggle) {
            mobileNotificationToggle.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();

                // Check if dropdown exists and is properly initialized
                const dropdownElement = document.getElementById('mobileNotificationDropdown');
                if (dropdownElement) {
                    // Create new dropdown instance if it doesn't exist
                    let dropdown = bootstrap.Dropdown.getInstance(dropdownElement);
                    if (!dropdown) {
                        dropdown = new bootstrap.Dropdown(dropdownElement);
                    }
                    dropdown.toggle();
                }
            });

            // Listen for dropdown show event
            const dropdownElement = document.getElementById('mobileNotificationDropdown');
            if (dropdownElement) {
                dropdownElement.addEventListener('show.bs.dropdown', () => {
                    fetchNotifications();
                });
            }
        }

        // Desktop notification toggle
        if (notificationDropdown) {
            notificationDropdown.addEventListener('click', (e) => {
                e.preventDefault();
            });

            // Listen for dropdown show event
            const dropdownElement = document.querySelector('.notification-dropdown');
            if (dropdownElement) {
                dropdownElement.addEventListener('show.bs.dropdown', () => {
                    fetchNotifications();
                });
            }
        }

        // Initial fetch and setup
        fetchNotifications();

        // Poll for new notifications every 30 seconds
        setInterval(fetchNotifications, 30000);
    });
</script>