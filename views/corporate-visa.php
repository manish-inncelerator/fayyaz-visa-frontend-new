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
    ->description('Streamline international business travel with our comprehensive corporate visa services trusted by companies worldwide')
    ->meta('author', 'Fayyaz Travels')
    ->meta('robots', 'index, follow')
    ->image($url . '/og/index.php?title=' . urlencode('Enterprise Visa Solutions for Global Business') . '&subtitle=' . urlencode('Streamline international business travel with our comprehensive corporate visa services trusted by companies worldwide') . '&gradient=' . urlencode('velvet_night'))
    ->canonical('https://fayyaztravels.com/visa');

// Convert both to strings and concatenate
$seo = $schema . "\n" . $metatags;
// SEO END

// Output HTML head and scripts
echo html_head('Enterprise Visa Solutions for Global Business', null, true, ['assets/css/corporate-visa.css'], false, false, $seo);


?>

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
<section class="hero-section text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 mb-5 mb-lg-0">
                <h1 class="display-4 fw-bold mb-4">Enterprise Visa Solutions for Global Business</h1>
                <p class="lead mb-4 opacity-85">Streamline international business travel with our comprehensive corporate visa services trusted by multinational enterprises worldwide.</p>
                <!-- <div class="d-flex flex-wrap gap-3 mt-5">
                    <a href="#services" class="btn btn-accent btn-lg px-4 shadow-sm">
                        <i class="bi bi-stars me-2"></i>Explore Solutions
                    </a>
                    <a href="#contact" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-headset me-2"></i>Request Consultation
                    </a>
                </div> -->
            </div>
            <div class="col-lg-1"></div>
            <div class="col-lg-6">
                <div class="bg-white text-dark p-5 rounded-4 shadow-lg">
                    <h3 class="fw-bold mb-4">Consultation Request Form</h3>
                    <p class="mb-4">Please fill out the form below to request a consultation with our corporate visa specialists. We look forward to assisting you with your visa management needs.</p>
                    <form id="consultationForm" novalidate>
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name" required>
                            <div class="invalid-feedback">Please enter your full name.</div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Work Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your work email (e.g., you@company.com)" required>
                            <small class="form-text text-muted">Personal emails like Gmail/Yahoo are not allowed.</small>
                            <div class="invalid-feedback">Please enter a valid work email address.</div>
                        </div>

                        <div class="mb-3">
                            <label for="companyName" class="form-label">Company Name</label>
                            <input type="text" class="form-control" id="companyName" name="companyName" placeholder="Enter your company name" required>
                            <div class="invalid-feedback">Please enter your company name.</div>
                        </div>

                        <div class="mb-3">
                            <label for="companyWebsite" class="form-label">Company Website</label>
                            <input type="text" class="form-control" id="companyWebsite" name="companyWebsite" placeholder="Enter your company website (e.g., company.com)" required>
                            <small class="form-text text-muted">Enter your company's official domain (without "www" or "http://").</small>
                            <div class="invalid-feedback">Please enter a valid company website.</div>
                        </div>

                        <div class="mb-3">
                            <label for="country" class="form-label">Country</label>
                            <select class="form-select" id="country" name="country" required>
                                <option value="">Select Country</option>
                                <option value="Afghanistan">Afghanistan</option>
                                <option value="Albania">Albania</option>
                                <option value="Algeria">Algeria</option>
                                <option value="Andorra">Andorra</option>
                                <option value="Angola">Angola</option>
                                <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                <option value="Argentina">Argentina</option>
                                <option value="Armenia">Armenia</option>
                                <option value="Australia">Australia</option>
                                <option value="Austria">Austria</option>
                                <option value="Azerbaijan">Azerbaijan</option>
                                <option value="Bahamas">Bahamas</option>
                                <option value="Bahrain">Bahrain</option>
                                <option value="Bangladesh">Bangladesh</option>
                                <option value="Barbados">Barbados</option>
                                <option value="Belarus">Belarus</option>
                                <option value="Belgium">Belgium</option>
                                <option value="Belize">Belize</option>
                                <option value="Benin">Benin</option>
                                <option value="Bhutan">Bhutan</option>
                                <option value="Bolivia">Bolivia</option>
                                <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                <option value="Botswana">Botswana</option>
                                <option value="Brazil">Brazil</option>
                                <option value="Brunei">Brunei</option>
                                <option value="Bulgaria">Bulgaria</option>
                                <option value="Burkina Faso">Burkina Faso</option>
                                <option value="Burundi">Burundi</option>
                                <option value="Cambodia">Cambodia</option>
                                <option value="Cameroon">Cameroon</option>
                                <option value="Canada">Canada</option>
                                <option value="Cape Verde">Cape Verde</option>
                                <option value="Central African Republic">Central African Republic</option>
                                <option value="Chad">Chad</option>
                                <option value="Chile">Chile</option>
                                <option value="China">China</option>
                                <option value="Colombia">Colombia</option>
                                <option value="Comoros">Comoros</option>
                                <option value="Congo">Congo</option>
                                <option value="Costa Rica">Costa Rica</option>
                                <option value="Croatia">Croatia</option>
                                <option value="Cuba">Cuba</option>
                                <option value="Cyprus">Cyprus</option>
                                <option value="Czech Republic">Czech Republic</option>
                                <option value="Denmark">Denmark</option>
                                <option value="Djibouti">Djibouti</option>
                                <option value="Dominica">Dominica</option>
                                <option value="Dominican Republic">Dominican Republic</option>
                                <option value="Ecuador">Ecuador</option>
                                <option value="Egypt">Egypt</option>
                                <option value="El Salvador">El Salvador</option>
                                <option value="Equatorial Guinea">Equatorial Guinea</option>
                                <option value="Eritrea">Eritrea</option>
                                <option value="Estonia">Estonia</option>
                                <option value="Eswatini">Eswatini</option>
                                <option value="Ethiopia">Ethiopia</option>
                                <option value="Fiji">Fiji</option>
                                <option value="Finland">Finland</option>
                                <option value="France">France</option>
                                <option value="Gabon">Gabon</option>
                                <option value="Gambia">Gambia</option>
                                <option value="Georgia">Georgia</option>
                                <option value="Germany">Germany</option>
                                <option value="Ghana">Ghana</option>
                                <option value="Greece">Greece</option>
                                <option value="Grenada">Grenada</option>
                                <option value="Guatemala">Guatemala</option>
                                <option value="Guinea">Guinea</option>
                                <option value="Guyana">Guyana</option>
                                <option value="Haiti">Haiti</option>
                                <option value="Honduras">Honduras</option>
                                <option value="Hungary">Hungary</option>
                                <option value="Iceland">Iceland</option>
                                <option value="India">India</option>
                                <option value="Indonesia">Indonesia</option>
                                <option value="Iran">Iran</option>
                                <option value="Iraq">Iraq</option>
                                <option value="Ireland">Ireland</option>
                                <option value="Israel">Israel</option>
                                <option value="Italy">Italy</option>
                                <option value="Jamaica">Jamaica</option>
                                <option value="Japan">Japan</option>
                                <option value="Jordan">Jordan</option>
                                <option value="Kazakhstan">Kazakhstan</option>
                                <option value="Kenya">Kenya</option>
                                <option value="Kuwait">Kuwait</option>
                                <option value="Laos">Laos</option>
                                <option value="Latvia">Latvia</option>
                                <option value="Lebanon">Lebanon</option>
                                <option value="Libya">Libya</option>
                                <option value="Lithuania">Lithuania</option>
                                <option value="Luxembourg">Luxembourg</option>
                                <option value="Madagascar">Madagascar</option>
                                <option value="Malaysia">Malaysia</option>
                                <option value="Maldives">Maldives</option>
                                <option value="Mali">Mali</option>
                                <option value="Malta">Malta</option>
                                <option value="Mexico">Mexico</option>
                                <option value="Moldova">Moldova</option>
                                <option value="Monaco">Monaco</option>
                                <option value="Mongolia">Mongolia</option>
                                <option value="Morocco">Morocco</option>
                                <option value="Nepal">Nepal</option>
                                <option value="Netherlands">Netherlands</option>
                                <option value="New Zealand">New Zealand</option>
                                <option value="Nigeria">Nigeria</option>
                                <option value="Norway">Norway</option>
                                <option value="Oman">Oman</option>
                                <option value="Pakistan">Pakistan</option>
                                <option value="Panama">Panama</option>
                                <option value="Paraguay">Paraguay</option>
                                <option value="Peru">Peru</option>
                                <option value="Philippines">Philippines</option>
                                <option value="Poland">Poland</option>
                                <option value="Portugal">Portugal</option>
                                <option value="Qatar">Qatar</option>
                                <option value="Romania">Romania</option>
                                <option value="Russia">Russia</option>
                                <option value="Saudi Arabia">Saudi Arabia</option>
                                <option value="Singapore">Singapore</option>
                                <option value="South Africa">South Africa</option>
                                <option value="Spain">Spain</option>
                                <option value="Sweden">Sweden</option>
                                <option value="Switzerland">Switzerland</option>
                                <option value="Thailand">Thailand</option>
                                <option value="Turkey">Turkey</option>
                                <option value="United Kingdom">United Kingdom</option>
                                <option value="United States">United States</option>
                                <option value="Vietnam">Vietnam</option>
                                <option value="Yemen">Yemen</option>
                                <option value="Zimbabwe">Zimbabwe</option>
                            </select>
                            <div class="invalid-feedback">Please select your country.</div>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Additional Information</label>
                            <textarea class="form-control" id="message" name="message" rows="4" placeholder="Please provide any additional information or questions"></textarea>
                        </div>

                        <button type="submit" class="btn btn-accent">Submit Consultation Request</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="section bg-white" id="services">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center mb-6">
                <h2 class="fw-bold section-title mb-3">Corporate Visa Solutions</h2>
                <p class="text-muted">Sophisticated solutions engineered for enterprise travel management and global workforce mobility optimization</p>
            </div>
        </div>

        <div class="row g-4">
            <!-- Proprietary Database -->
            <div class="col-md-6 col-lg-3">
                <div class="service-card card p-4 h-100 border-0 shadow-sm hover-lift">
                    <div class="service-icon mx-auto">
                        <i class="bi bi-database"></i>
                    </div>
                    <h3 class="h5 mb-3 text-center mt-4">Global Visa Intelligence</h3>
                    <p class="text-muted mb-0 small">Our proprietary database monitors real-time visa requirements across 50+ jurisdictions, featuring compliance alerts and comprehensive documentation protocols.</p>
                </div>
            </div>

            <!-- Dedicated Customer Care -->
            <div class="col-md-6 col-lg-3">
                <div class="service-card card p-4 h-100 border-0 shadow-sm hover-lift">
                    <div class="service-icon mx-auto" style="background: linear-gradient(135deg, #10b981, #059669);">
                        <i class="bi bi-headset"></i>
                    </div>
                    <h3 class="h5 mb-3 text-center mt-4">Executive Account Management</h3>
                    <p class="text-muted mb-0 small">Personalized service with dedicated account executives and 24/7 priority response for mission-critical travel requirements.</p>
                </div>
            </div>

            <!-- Global Coverage -->
            <div class="col-md-6 col-lg-3">
                <div class="service-card card p-4 h-100 border-0 shadow-sm hover-lift">
                    <div class="service-icon mx-auto" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                        <i class="bi bi-globe2"></i>
                    </div>
                    <h3 class="h5 mb-3 text-center mt-4">Global Processing Infrastructure</h3>
                    <p class="text-muted mb-0 small">Specialized regional expertise through our network of 60+ international offices with established diplomatic relationships in key commercial hubs.</p>
                </div>
            </div>

            <!-- Embassy Updates -->
            <div class="col-md-6 col-lg-3">
                <div class="service-card card p-4 h-100 border-0 shadow-sm hover-lift">
                    <div class="service-icon mx-auto" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                        <i class="bi bi-bell"></i>
                    </div>
                    <h3 class="h5 mb-3 text-center mt-4">Regulatory Intelligence</h3>
                    <p class="text-muted mb-0 small">Strategic updates on diplomatic policy changes, geopolitical developments, and security advisories affecting your international operations.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="section-sm bg-light-custom">
    <div class="container py-5">
        <div class="row g-4 text-center">
            <div class="col-md-3">
                <div class="stat-number">15</div>
                <div class="text-uppercase small fw-bold text-muted">Years of Excellence</div>
                <div class="text-muted small mt-2">Delivering unparalleled service to global enterprises since 2010.</div>
            </div>
            <div class="col-md-3">
                <div class="stat-number">50+</div>
                <div class="text-uppercase small fw-bold text-muted">Jurisdictions</div>
                <div class="text-muted small mt-2">Facilitating visa procurement for all major global markets.</div>
            </div>
            <div class="col-md-3">
                <div class="stat-number">99.5%</div>
                <div class="text-uppercase small fw-bold text-muted">Success Rate</div>
                <div class="text-muted small mt-2">Establishing industry benchmarks with our approval metrics.</div>
            </div>
            <div class="col-md-3">
                <div class="stat-number">24/7</div>
                <div class="text-uppercase small fw-bold text-muted">Executive Support</div>
                <div class="text-muted small mt-2">Continuous availability for time-sensitive international travel requirements.</div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="section bg-white">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1450101499163-c8848c66ca85?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Corporate visa services" class="img-fluid rounded-4 shadow-lg">
            </div>
            <div class="col-lg-6">
                <h2 class="fw-bold mb-4">Enterprise-Grade Visa Management Platform</h2>
                <div class="feature-highlight mb-4 p-4 border-start border-3 bg-light bg-opacity-50 rounded-end" style="border-left: 2px solid var(--accent); border-right:none; border-top:none;border-bottom:none;">
                    <h3 class="h5 mb-2">Centralized Administrative Console</h3>
                    <p class="text-muted mb-0 small">Monitor all personnel visa applications through a unified interface with real-time status tracking and document management capabilities.</p>
                </div>
                <div class="feature-highlight mb-4 p-4 border-start border-3 bg-light bg-opacity-50 rounded-end" style="border-left: 2px solid var(--accent); border-right:none; border-top:none;border-bottom:none;">
                    <h3 class="h5 mb-2">High-Volume Processing</h3>
                    <p class="text-muted mb-0 small">Execute multiple visa applications concurrently with our enterprise-grade workflow optimization tools.</p>
                </div>
                <div class="feature-highlight mb-4 p-4 border-start border-3 bg-light bg-opacity-50 rounded-end" style="border-left: 2px solid var(--accent); border-right:none; border-top:none;border-bottom:none;">
                    <h3 class="h5 mb-2">Compliance Analytics</h3>
                    <p class="text-muted mb-0 small">Generate comprehensive reports for audit purposes and regulatory compliance documentation requirements.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section text-white" style="background: linear-gradient(rgba(20, 56, 92, 0.95), rgba(20, 56, 92, 0.98)),
    url('https://images.unsplash.com/photo-1552581234-26160f608093?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');" id="contact">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="fw-bold mb-4">Transform Your Corporate Travel Operations</h2>
                <p class="lead mb-5 opacity-85">Arrange a strategic consultation with our enterprise solutions team to develop a bespoke visa management framework for your organization.</p>

                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="corporate-visa#top" class="btn btn-accent btn-lg px-5 shadow">
                        <i class="bi bi-envelope me-2"></i> Request Enterprise Consultation
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Footer -->
<?php require 'components/Footer.php'; ?>


<?php
echo html_scripts(
    includeJQuery: false,
    includeBootstrap: true,
    customScripts: [''],
    includeSwal: true,
    includeNotiflix: true
);
?>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM fully loaded'); // Debug confirmation
        alert('DOM fully loaded');

        const form = document.getElementById('consultationForm');

        if (!form) {
            console.error('Form element not found!');
            return;
        }

        console.log('Form element found:', form); // Debug confirmation

        // Validate email domain matches company website
        function validateEmailDomain(email, website) {
            try {
                const emailDomain = email.substring(email.lastIndexOf("@") + 1).toLowerCase();
                let companyDomain = website.toLowerCase()
                    .replace(/^www\./, '')
                    .replace(/^https?:\/\//, '')
                    .split('/')[0];

                console.log('Comparing domains:', emailDomain, companyDomain); // Debug
                return emailDomain === companyDomain;
            } catch (e) {
                console.error('Domain validation error:', e);
                return false;
            }
        }

        // Enhanced form submission handler
        form.addEventListener('submit', async function(event) {
            console.log('Form submission initiated'); // Debug
            event.preventDefault();
            event.stopPropagation();

            // Validate form
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                console.log('Form validation failed'); // Debug
                return;
            }

            // Get form data
            const formData = {
                name: form.name.value.trim(),
                email: form.email.value.trim(),
                companyName: form.companyName.value.trim(),
                companyWebsite: form.companyWebsite.value.trim(),
                country: form.country.value,
                message: form.message.value.trim()
            };

            console.log('Form data:', formData); // Debug

            // Validate email domain
            if (!validateEmailDomain(formData.email, formData.companyWebsite)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Domain Mismatch',
                    text: 'Your work email domain must match your company website domain'
                });
                return;
            }

            try {
                // Show loading state
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';

                // Submit form - using absolute path to API endpoint
                const response = await fetch('/api/v1/corporate-visa.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                console.log('Response status:', response.status); // Debug

                if (!response.ok) {
                    const errorText = await response.text();
                    throw new Error(`Server responded with ${response.status}: ${errorText}`);
                }

                const data = await response.json();
                console.log('Response data:', data); // Debug

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Your consultation request has been submitted'
                    });
                    form.reset();
                    form.classList.remove('was-validated');
                } else {
                    throw new Error(data.error || 'Submission failed');
                }
            } catch (error) {
                console.error('Submission error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Submission Failed',
                    text: error.message || 'An error occurred while submitting your request'
                });
            } finally {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Submit Consultation Request';
                }
            }
        });

        // Real-time validation feedback
        form.querySelectorAll('input, select, textarea').forEach(input => {
            input.addEventListener('input', function() {
                if (this.checkValidity()) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                }
            });
        });
    });
</script>


</body>

</html>