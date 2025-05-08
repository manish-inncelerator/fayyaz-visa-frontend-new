<?php
$current_url = 'https://www.fayyaztravels.com/visa/';
?>
<style>
    :root {
        --brown: #543019;
        --brown-light: #7a4a2d;
        --brown-lighter: #a06441;
        --brown-dark: #3d2412;
        --brown-darker: #27180c;
        --golden: #af8700;
        --golden-light: #d6a500;
        --golden-lighter: #ffc300;
        --golden-dark: #876900;
        --golden-darker: #5f4b00;
        --blue: #14385C;
        --blue-light: #3a6e8f;
        --blue-lighter: #6699b3;
        --blue-dark: #0c2c44;
        --blue-darker: #081c2d;
    }

    .text-golden {
        color: var(--golden);
    }

    .bg-golden {
        background-color: var(--golden);
    }

    .bg-blue {
        background-color: var(--blue);
    }

    .text-blue-lighter {
        color: var(--blue-lighter);
    }

    .text-blue-dark {
        color: var(--blue-dark);
    }

    .text-brown-lighter {
        color: var(--brown-lighter);
    }

    .btn-golden {
        background-color: var(--golden);
        border-color: var(--golden);
        color: white;
    }

    .btn-outline-golden {
        border-color: var(--golden);
        color: var(--golden);
    }

    .btn-outline-golden:hover {
        background-color: var(--golden);
        color: white;
    }

    .testimonial-section {
        background: linear-gradient(135deg, var(--blue-darker) 0%, var(--blue-dark) 100%);
        position: relative;
        overflow: hidden;
    }

    .testimonial-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        opacity: 0.4;
    }

    .testimonial-card {
        transition: all 0.4s ease;
        border-left: 4px solid var(--golden);
        background: linear-gradient(to bottom, rgba(255, 255, 255, 1), rgba(245, 245, 245, 1));
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .testimonial-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2) !important;
    }

    .quote-icon {
        font-size: 3rem;
        color: rgba(175, 135, 0, 0.1);
        transform: rotate(180deg);
    }

    .client-avatar {
        border: 3px solid var(--golden-light);
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    .status-dot {
        width: 12px;
        height: 12px;
        border: 2px solid white;
    }

    @keyframes float {
        0% {
            transform: translateY(0) rotate(0deg);
        }

        50% {
            transform: translateY(-15px) rotate(2deg);
        }

        100% {
            transform: translateY(0) rotate(0deg);
        }
    }

    .floating-element {
        animation: float 10s infinite ease-in-out;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    /* Owl Carousel Fixes */
    .testimonial-carousel .owl-stage {
        display: flex;
    }

    .testimonial-carousel .owl-item {
        display: flex;
        height: auto;
    }

    .testimonial-carousel .item {
        display: block !important;
        opacity: 1 !important;
        height: 100%;
    }

    /* Animations */
    [data-animate] {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.8s forwards;
    }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .delay-1 {
        animation-delay: 0.2s;
    }

    .delay-2 {
        animation-delay: 0.4s;
    }

    .delay-3 {
        animation-delay: 0.6s;
    }

    /* Enhanced Owl Carousel custom styles */
    .owl-nav {
        position: absolute;
        top: 50%;
        width: 100%;
        transform: translateY(-50%);
        display: flex;
        justify-content: space-between;
        pointer-events: none;
        padding: 0 -15px;
    }

    .owl-nav button.owl-prev,
    .owl-nav button.owl-next {
        width: 50px !important;
        height: 50px !important;
        border-radius: 50% !important;
        background: rgba(255, 255, 255, 0.9) !important;
        backdrop-filter: blur(5px);
        border: none !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15) !important;
        color: var(--blue-dark) !important;
        font-size: 24px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        transition: all 0.3s ease !important;
        pointer-events: auto;
        margin: 0 10px;
        opacity: 0.7;
    }

    .owl-nav button.owl-prev:hover,
    .owl-nav button.owl-next:hover {
        background: var(--golden) !important;
        color: white !important;
        opacity: 1;
        transform: scale(1.1);
    }

    .owl-nav button.owl-prev i,
    .owl-nav button.owl-next i {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .owl-dots {
        display: flex;
        justify-content: center;
        margin-top: 30px;
    }

    .owl-dots button.owl-dot {
        width: 10px;
        height: 10px;
        border-radius: 50px;
        margin: 0 5px;
        background-color: rgba(255, 255, 255, 0.3) !important;
        transition: all 0.3s ease;
    }

    .owl-dots button.owl-dot.active {
        background-color: var(--golden) !important;
        width: 25px;
    }

    .owl-dots button.owl-dot:hover {
        background-color: var(--golden-light) !important;
    }

    /* Enhanced section title */
    .section-title-line {
        height: 2px;
        background: linear-gradient(to right, transparent, var(--golden), transparent);
        width: 100%;
    }

    .section-title-icon {
        color: var(--golden);
        font-size: 1.2rem;
        transform: rotate(45deg);
        display: inline-block;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: rotate(45deg) scale(1);
        }

        50% {
            transform: rotate(45deg) scale(1.2);
        }

        100% {
            transform: rotate(45deg) scale(1);
        }
    }

    /* Google review card enhancement */
    .google-review-card {
        border-top: 5px solid #4285F4;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    .google-review-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        width: 150px;
        height: 150px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200' viewBox='0 0 200 200'%3E%3Ccircle fill='%23EA4335' fill-opacity='0.05' cx='100' cy='100' r='100'/%3E%3C/svg%3E");
        z-index: -1;
    }

    .rating-number {
        font-weight: 700;
        font-size: 3.5rem;
        color: var(--blue);
        text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.1);
        line-height: 1;
    }

    /* Card gradient hover effect */
    .testimonial-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(45deg, rgba(20, 56, 92, 0.05), rgba(175, 135, 0, 0.05));
        z-index: -1;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .testimonial-card:hover::before {
        opacity: 1;
    }
</style>

<section class="testimonial-section py-5 position-relative overflow-hidden mt-5">
    <!-- Decorative Elements -->
    <div class="position-absolute top-0 start-0 w-100 h-100 overflow-hidden">
        <div class="floating-element position-absolute" style="width: 250px; height: 250px; top: -80px; left: -50px;"></div>
        <div class="floating-element position-absolute" style="width: 200px; height: 200px; bottom: -70px; right: -50px; animation-delay: 2s;"></div>
        <div class="floating-element position-absolute" style="width: 150px; height: 150px; top: 30%; right: 10%; animation-delay: 1s; animation-duration: 15s;"></div>
        <div class="floating-element position-absolute" style="width: 100px; height: 100px; bottom: 20%; left: 15%; animation-delay: 3s; animation-duration: 12s;"></div>
    </div>

    <div class="container position-relative">
        <!-- Enhanced Section Header -->
        <div class="row justify-content-center mb-5" data-animate>
            <div class="col-lg-8 text-center">
                <span class="d-inline-block text-golden text-uppercase fw-bold mb-3 px-3 py-1 rounded-pill" style="letter-spacing: 2px; background-color: rgba(175, 135, 0, 0.1);">Client Voices</span>
                <h2 class="display-5 fw-bold text-white mb-4">Trusted Experiences</h2>
                <div class="d-flex align-items-center justify-content-center mb-4">
                    <div class="section-title-line flex-grow-1"></div>
                    <div class="px-3"><i class="bi bi-diamond-fill section-title-icon"></i></div>
                    <div class="section-title-line flex-grow-1"></div>
                </div>
                <p class="lead text-blue-lighter mb-0">Discover what our valued clients say about their journeys with us</p>
            </div>
        </div>

        <!-- Enhanced Testimonial Carousel -->
        <div class="row">
            <div class="col-12">
                <div class="owl-carousel testimonial-carousel">
                    <!-- Enhanced Google Review Card -->
                    <div class="item" data-animate>
                        <div class="card h-100 border-0 shadow-lg overflow-hidden google-review-card">
                            <div class="card-body p-4 d-flex flex-column text-center">
                                <div class="d-flex align-items-center justify-content-center mb-4">
                                    <div class="bg-light rounded-circle p-3 me-3 shadow-sm">
                                        <img src="assets/images/google_icon.png" alt="Google" style="width: 36px; height: 36px;">
                                    </div>
                                    <h3 class="h4 mb-0 text-blue-dark fw-bold">Google Reviews</h3>
                                </div>

                                <div class="my-auto py-3">
                                    <div class="rating-number mb-2" data-target="4.8">4.8</div>
                                    <div class="mb-3">
                                        <i class="bi bi-star-fill text-golden fs-4"></i>
                                        <i class="bi bi-star-fill text-golden fs-4"></i>
                                        <i class="bi bi-star-fill text-golden fs-4"></i>
                                        <i class="bi bi-star-fill text-golden fs-4"></i>
                                        <i class="bi bi-star-half text-golden fs-4"></i>
                                    </div>
                                    <p class="text-brown-lighter mb-0">Based on <span class="review-counter fw-bold" data-target="270">270</span>+ reviews</p>
                                </div>

                                <a href="#" class="btn mt-auto py-3 px-4 fw-bold d-inline-flex align-items-center justify-content-center rounded-pill" style="background-color: var(--blue); border-color: var(--blue); color: white;">
                                    Read All Reviews
                                    <i class="bi bi-arrow-right-short ms-2 fs-5"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Testimonial Cards -->
                    <div class="item" data-animate>
                        <div class="card h-100 border-0 shadow-lg overflow-hidden testimonial-card">
                            <div class="card-body p-4 position-relative">
                                <i class="bi bi-quote quote-icon position-absolute" style="top: 10px; right: 20px;"></i>

                                <div class="mb-4">
                                    <div class="mb-3">
                                        <i class="bi bi-star-fill text-golden"></i>
                                        <i class="bi bi-star-fill text-golden"></i>
                                        <i class="bi bi-star-fill text-golden"></i>
                                        <i class="bi bi-star-fill text-golden"></i>
                                        <i class="bi bi-star-fill text-golden"></i>
                                    </div>
                                    <p class="text-blue-dark mb-0">I had an excellent experience working with Kimmy to get my Visa. She guided me through every step of the process with great clarity and professionalism.</p>
                                </div>

                                <div class="d-flex align-items-center pt-3 border-top" style="border-color: rgba(84, 48, 25, 0.1) !important;">
                                    <div class="position-relative me-3">
                                        <img src="<?= $current_url; ?>assets/images/rev/saurabh.png" class="rounded-circle client-avatar" style="width: 60px; height: 60px; object-fit: cover;">
                                        <span class="position-absolute bottom-0 end-0 bg-success rounded-circle status-dot"></span>
                                    </div>
                                    <div>
                                        <h5 class="mb-1 text-blue">Saurabh</h5>
                                        <small class="d-flex align-items-center text-brown-lighter">
                                            <i class="bi bi-patch-check-fill text-success me-1"></i>
                                            Verified Customer
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="item" data-animate>
                        <div class="card h-100 border-0 shadow-lg overflow-hidden testimonial-card">
                            <div class="card-body p-4 position-relative">
                                <i class="bi bi-quote quote-icon position-absolute" style="top: 10px; right: 20px;"></i>

                                <div class="mb-4">
                                    <div class="mb-3">
                                        <i class="bi bi-star-fill text-golden"></i>
                                        <i class="bi bi-star-fill text-golden"></i>
                                        <i class="bi bi-star-fill text-golden"></i>
                                        <i class="bi bi-star-fill text-golden"></i>
                                        <i class="bi bi-star-fill text-golden"></i>
                                    </div>
                                    <p class="text-blue-dark mb-0">I recently enlisted the help of Fayyaz Travels to secure a visa for my relative to visit Singapore. The entire experience was seamless and stress-free.</p>
                                </div>

                                <div class="d-flex align-items-center pt-3 border-top" style="border-color: rgba(84, 48, 25, 0.1) !important;">
                                    <div class="position-relative me-3">
                                        <img src="<?= $current_url; ?>assets/images/rev/Khalil.png" class="rounded-circle client-avatar" style="width: 60px; height: 60px; object-fit: cover;">
                                        <span class="position-absolute bottom-0 end-0 bg-success rounded-circle status-dot"></span>
                                    </div>
                                    <div>
                                        <h5 class="mb-1 text-blue">Khalil Effati Daryani</h5>
                                        <small class="d-flex align-items-center text-brown-lighter">
                                            <i class="bi bi-patch-check-fill text-success me-1"></i>
                                            Verified Customer
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Third testimonial card -->
                    <div class="item" data-animate>
                        <div class="card h-100 border-0 shadow-lg overflow-hidden testimonial-card">
                            <div class="card-body p-4 position-relative">
                                <i class="bi bi-quote quote-icon position-absolute" style="top: 10px; right: 20px;"></i>

                                <div class="mb-4">
                                    <div class="mb-3">
                                        <i class="bi bi-star-fill text-golden"></i>
                                        <i class="bi bi-star-fill text-golden"></i>
                                        <i class="bi bi-star-fill text-golden"></i>
                                        <i class="bi bi-star-fill text-golden"></i>
                                        <i class="bi bi-star-fill text-golden"></i>
                                    </div>
                                    <p class="text-blue-dark mb-0">I've engaged the services of this travel agency three times now for my Schengen visa applications. They systematically prepare all required documents.</p>
                                </div>

                                <div class="d-flex align-items-center pt-3 border-top" style="border-color: rgba(84, 48, 25, 0.1) !important;">
                                    <div class="position-relative me-3">
                                        <img src="<?= $current_url; ?>assets/images/rev/Xinelli.png" class="rounded-circle client-avatar" style="width: 60px; height: 60px; object-fit: cover;">
                                        <span class="position-absolute bottom-0 end-0 bg-success rounded-circle status-dot"></span>
                                    </div>
                                    <div>
                                        <h5 class="mb-1 text-blue">Xinelli Dharani</h5>
                                        <small class="d-flex align-items-center text-brown-lighter">
                                            <i class="bi bi-patch-check-fill text-success me-1"></i>
                                            Verified Customer
                                        </small>
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

<!-- Load required libraries -->
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css"> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Owl Carousel first
        $(".testimonial-carousel").owlCarousel({
            loop: true,
            margin: 20,
            nav: true,
            dots: true,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            smartSpeed: 800,
            navText: [
                "<i class='bi bi-arrow-left'></i>",
                "<i class='bi bi-arrow-right'></i>"
            ],
            responsive: {
                0: {
                    items: 1,
                    margin: 10
                },
                576: {
                    items: 1,
                    margin: 15
                },
                768: {
                    items: 2,
                    margin: 15
                },
                992: {
                    items: 3,
                    margin: 20
                }
            },
            onInitialized: function() {
                $('.owl-carousel').addClass('testimonial-enhanced');
                $('.owl-item.active').addClass('animated');

                if (window.innerWidth < 768) {
                    const swipeHint = document.createElement('div');
                    swipeHint.className = 'text-center text-white-50 mt-2 small';
                    swipeHint.innerHTML = '<i class="bi bi-arrow-left-right me-1"></i> Swipe to see more testimonials';
                    document.querySelector('.owl-dots').parentNode.appendChild(swipeHint);

                    setTimeout(() => {
                        swipeHint.style.opacity = '0';
                        swipeHint.style.transition = 'opacity 1s ease';
                    }, 5000);
                }
            }
        });

        // Enhanced Counter animation
        const ratingElements = document.querySelectorAll('.rating-number');
        const reviewElements = document.querySelectorAll('.review-counter');
        const animationDuration = 1500;

        function animateCounter(element, targetValue) {
            const startValue = 0;
            const increment = targetValue / (animationDuration / 16);
            let currentValue = startValue;

            function updateValue() {
                if (currentValue < targetValue) {
                    currentValue += increment;
                    element.innerText = element.classList.contains('rating-number') ?
                        Math.min(targetValue, Math.round(currentValue * 10) / 10).toFixed(1) :
                        Math.min(targetValue, Math.round(currentValue));
                    requestAnimationFrame(updateValue);
                } else {
                    element.innerText = targetValue.toFixed(targetValue % 1 ? 1 : 0);
                }
            }

            updateValue();
        }

        // Initialize counters when elements are visible in viewport
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const counterObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const targetValue = entry.target.classList.contains('rating-number') ?
                        parseFloat(entry.target.getAttribute('data-target')) :
                        parseInt(entry.target.getAttribute('data-target'));
                    animateCounter(entry.target, targetValue);
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        ratingElements.forEach(element => counterObserver.observe(element));
        reviewElements.forEach(element => counterObserver.observe(element));

        // Enhanced Animate elements on scroll
        const animateElements = () => {
            const elements = document.querySelectorAll('[data-animate]');
            elements.forEach(element => {
                const elementPosition = element.getBoundingClientRect().top;
                const viewportHeight = window.innerHeight / 1.1;

                if (elementPosition < viewportHeight) {
                    const delayClass = element.classList.contains('delay-1') ? 0.2 :
                        element.classList.contains('delay-2') ? 0.4 :
                        element.classList.contains('delay-3') ? 0.6 : 0;

                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                    element.style.transition = `opacity 0.8s ease-out ${delayClass}s, transform 0.8s ease-out ${delayClass}s`;
                }
            });
        };

        window.addEventListener('scroll', animateElements);
        window.addEventListener('resize', animateElements);
        animateElements(); // Run once on load

        // Add some parallax effects on mousemove for desktop 
        if (window.innerWidth > 992) {
            const section = document.querySelector('.testimonial-section');
            const floatingElements = document.querySelectorAll('.floating-element');
            const cards = document.querySelectorAll('.testimonial-card');

            section.addEventListener('mousemove', (e) => {
                const x = e.clientX / window.innerWidth;
                const y = e.clientY / window.innerHeight;

                // Move floating elements
                floatingElements.forEach((element, index) => {
                    const speed = 0.03 + (index * 0.01);
                    const xOffset = (x - 0.5) * 50 * speed;
                    const yOffset = (y - 0.5) * 50 * speed;

                    element.style.transform = `translate(${xOffset}px, ${yOffset}px)`;
                });

                // Subtle tilt effect on cards
                cards.forEach((card) => {
                    const rect = card.getBoundingClientRect();
                    const cardX = e.clientX - rect.left - rect.width / 2;
                    const cardY = e.clientY - rect.top - rect.height / 2;

                    const tiltX = cardY / rect.height * 5;
                    const tiltY = -cardX / rect.width * 5;

                    if (Math.abs(cardX) < rect.width && Math.abs(cardY) < rect.height) {
                        card.style.transform = `perspective(1000px) rotateX(${tiltX}deg) rotateY(${tiltY}deg)`;
                    } else {
                        card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0)';
                    }
                });
            });

            // Reset transformations when mouse leaves the section
            section.addEventListener('mouseleave', () => {
                floatingElements.forEach((element) => {
                    element.style.transform = 'translate(0, 0)';
                });

                cards.forEach((card) => {
                    card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0)';
                });
            });
        }
    });
</script>