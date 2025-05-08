<?php session_start(); ?>
<?php require 'database.php'; ?>
<?php require 'currencyConverter.php'; ?>
<?php if (isset($params['country'])) : ?>
    <?php $countryName = e($params['country']) ?>
<?php else : ?>
    <h1 class="error">Country Missing</h1>
    <?php exit; ?>
<?php endif; ?>
<?php
// Check if the reset timestamp exists
if (!isset($_SESSION['last_reset'])) {
    $_SESSION['last_reset'] = time(); // Set the current timestamp on first visit
    $_SESSION['visit_count'] = 0;     // Initialize visit count
}

// Check if 5 minutes have passed since the last reset
if (time() - $_SESSION['last_reset'] > 300) { // 300 seconds = 5 minutes
    $_SESSION['last_reset'] = time();  // Reset timestamp
    $_SESSION['visit_count'] = 0;      // Reset visit count
}
$_SESSION['visit_count']++; // Increment visit count

function getVisitCount()
{
    return isset($_SESSION['visit_count']) ? $_SESSION['visit_count'] : 0;
}

?>
<?php
// Get the current page URL
$pageUrl = $_SERVER['REQUEST_URI'];  // This will fetch the current page URL

// Check if the "viewed" cookie is set for this page
$cookieName = 'viewed_' . md5($pageUrl);  // Unique cookie name based on the URL

// If the cookie is not set, increase the view count and set the cookie
if (!isset($_COOKIE[$cookieName])) {
    // Check if the record for this URL already exists
    $record = $database->get('visa_pageviews', ['id', 'views'], ['page_url' => $pageUrl]);

    if ($record) {
        // Update the existing view count for this URL
        $newViewCount = $record['views'] + 1;
        $database->update('visa_pageviews', ['views' => $newViewCount], ['id' => $record['id']]);
    } else {
        // Insert new record for this URL with initial views count
        $database->insert('visa_pageviews', [
            'page_url' => $pageUrl,
            'views' => 1
        ]);
    }

    // Set a cookie to prevent incrementing views again within the same period (e.g., 24 hours)
    setcookie($cookieName, '1', time() + 86400, "/");  // Expire after 1 day (86400 seconds)
}

// Fetch the updated view count for the current URL
$views = $database->get('visa_pageviews', 'views', ['page_url' => $pageUrl]);
?>
<?php
function countryToEmoji($countryName)
{
    // Full list of country names mapped to ISO 3166-1 alpha-2 codes
    $countries = [
        "Afghanistan" => "AF",
        "Albania" => "AL",
        "Algeria" => "DZ",
        "Andorra" => "AD",
        "Angola" => "AO",
        "Antigua and Barbuda" => "AG",
        "Argentina" => "AR",
        "Armenia" => "AM",
        "Australia" => "AU",
        "Austria" => "AT",
        "Azerbaijan" => "AZ",
        "Bahamas" => "BS",
        "Bahrain" => "BH",
        "Bangladesh" => "BD",
        "Barbados" => "BB",
        "Belarus" => "BY",
        "Belgium" => "BE",
        "Belize" => "BZ",
        "Benin" => "BJ",
        "Bhutan" => "BT",
        "Bolivia" => "BO",
        "Bosnia and Herzegovina" => "BA",
        "Botswana" => "BW",
        "Brazil" => "BR",
        "Brunei" => "BN",
        "Bulgaria" => "BG",
        "Burkina Faso" => "BF",
        "Burundi" => "BI",
        "Cabo Verde" => "CV",
        "Cambodia" => "KH",
        "Cameroon" => "CM",
        "Canada" => "CA",
        "Central African Republic" => "CF",
        "Chad" => "TD",
        "Chile" => "CL",
        "China" => "CN",
        "Colombia" => "CO",
        "Comoros" => "KM",
        "Congo (Congo-Brazzaville)" => "CG",
        "Congo (Congo-Kinshasa)" => "CD",
        "Costa Rica" => "CR",
        "Croatia" => "HR",
        "Cuba" => "CU",
        "Cyprus" => "CY",
        "Czechia" => "CZ",
        "Denmark" => "DK",
        "Djibouti" => "DJ",
        "Dominica" => "DM",
        "Dominican Republic" => "DO",
        "Ecuador" => "EC",
        "Egypt" => "EG",
        "El Salvador" => "SV",
        "Equatorial Guinea" => "GQ",
        "Eritrea" => "ER",
        "Estonia" => "EE",
        "Eswatini" => "SZ",
        "Ethiopia" => "ET",
        "Fiji" => "FJ",
        "Finland" => "FI",
        "France" => "FR",
        "Gabon" => "GA",
        "Gambia" => "GM",
        "Georgia" => "GE",
        "Germany" => "DE",
        "Ghana" => "GH",
        "Greece" => "GR",
        "Grenada" => "GD",
        "Guatemala" => "GT",
        "Guinea" => "GN",
        "Guinea-Bissau" => "GW",
        "Guyana" => "GY",
        "Haiti" => "HT",
        "Honduras" => "HN",
        "Hungary" => "HU",
        "Iceland" => "IS",
        "India" => "IN",
        "Indonesia" => "ID",
        "Iran" => "IR",
        "Iraq" => "IQ",
        "Ireland" => "IE",
        "Israel" => "IL",
        "Italy" => "IT",
        "Jamaica" => "JM",
        "Japan" => "JP",
        "Jordan" => "JO",
        "Kazakhstan" => "KZ",
        "Kenya" => "KE",
        "Kiribati" => "KI",
        "Kuwait" => "KW",
        "Kyrgyzstan" => "KG",
        "Laos" => "LA",
        "Latvia" => "LV",
        "Lebanon" => "LB",
        "Lesotho" => "LS",
        "Liberia" => "LR",
        "Libya" => "LY",
        "Liechtenstein" => "LI",
        "Lithuania" => "LT",
        "Luxembourg" => "LU",
        "Madagascar" => "MG",
        "Malawi" => "MW",
        "Malaysia" => "MY",
        "Maldives" => "MV",
        "Mali" => "ML",
        "Malta" => "MT",
        "Mauritania" => "MR",
        "Mauritius" => "MU",
        "Mexico" => "MX",
        "Moldova" => "MD",
        "Monaco" => "MC",
        "Mongolia" => "MN",
        "Montenegro" => "ME",
        "Morocco" => "MA",
        "Mozambique" => "MZ",
        "Myanmar" => "MM",
        "Namibia" => "NA",
        "Nauru" => "NR",
        "Nepal" => "NP",
        "Netherlands" => "NL",
        "New Zealand" => "NZ",
        "Nicaragua" => "NI",
        "Niger" => "NE",
        "Nigeria" => "NG",
        "North Korea" => "KP",
        "North Macedonia" => "MK",
        "Norway" => "NO",
        "Oman" => "OM",
        "Pakistan" => "PK",
        "Palau" => "PW",
        "Panama" => "PA",
        "Papua New Guinea" => "PG",
        "Paraguay" => "PY",
        "Peru" => "PE",
        "Philippines" => "PH",
        "Poland" => "PL",
        "Portugal" => "PT",
        "Qatar" => "QA",
        "Romania" => "RO",
        "Russia" => "RU",
        "Rwanda" => "RW",
        "Saudi Arabia" => "SA",
        "Senegal" => "SN",
        "Serbia" => "RS",
        "Seychelles" => "SC",
        "Sierra Leone" => "SL",
        "Singapore" => "SG",
        "Slovakia" => "SK",
        "Slovenia" => "SI",
        "Solomon Islands" => "SB",
        "Somalia" => "SO",
        "South Africa" => "ZA",
        "South Korea" => "KR",
        "Spain" => "ES",
        "Sri Lanka" => "LK",
        "Sudan" => "SD",
        "Sweden" => "SE",
        "Switzerland" => "CH",
        "Syria" => "SY",
        "Tajikistan" => "TJ",
        "Tanzania" => "TZ",
        "Thailand" => "TH",
        "Togo" => "TG",
        "Tonga" => "TO",
        "Tunisia" => "TN",
        "Turkey" => "TR",
        "Uganda" => "UG",
        "Ukraine" => "UA",
        "United Arab Emirates" => "AE",
        "United Kingdom" => "GB",
        "United States" => "US",
        "United States of America" => "US",
        "Uruguay" => "UY",
        "Uzbekistan" => "UZ",
        "Vanuatu" => "VU",
        "Vatican City" => "VA",
        "Venezuela" => "VE",
        "Vietnam" => "VN",
        "Yemen" => "YE",
        "Zambia" => "ZM",
        "Zimbabwe" => "ZW"
    ];

    // Normalize input
    $countryName = ucwords(strtolower(trim($countryName)));

    // Check if country exists
    if (!isset($countries[$countryName])) {
        return "❓"; // Return unknown emoji if not found
    }

    // Get ISO code
    $countryCode = strtoupper($countries[$countryName]);

    // Convert to emoji flag using mb_chr
    $emojiFlag = '';
    foreach (str_split($countryCode) as $letter) {
        $emojiFlag .= mb_chr(ord($letter) - ord('A') + 0x1F1E6, 'UTF-8');
    }

    return $emojiFlag;
}
?>

<?php
$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

// Get the slug from the URL (e.g. "apply-for-united-arab-emirates-visa-online")
$slug = basename(parse_url($current_url, PHP_URL_PATH));

// Remove the "apply-for-" prefix
$slug = preg_replace('/^apply-for-/', '', $slug);

// Extract the part before "-visa"
$countrySlug = explode('-visa', $slug)[0];

// Replace hyphens with spaces and capitalize words
$country_name = ucwords(str_replace('-', ' ', trim($countrySlug)));

defined('BASE_DIR') || die('Direct access denied');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include required files
require 'inc/html_head.php';
require 'inc/html_foot.php';
require 'min.php';
require 'imgClass.php';

/**
 * Fetch country details and images by country name.
 *
 * @param string $countryName The name of the country to fetch.
 * @return array Country data with images.
 */

function fetchCountryDetails($countryName, $includeImages = false)
{
    global $database;

    try {
        // Fetch country details
        $countryDetails = $database->select(
            'visa_countries',
            [
                '[>]visa_types' => ['visa_type' => 'id'],
                '[>]visa_kinds' => ['visa_kind' => 'id']
            ],
            [
                'visa_countries.id',
                'visa_countries.country_id',
                'visa_countries.seo_title',
                'visa_countries.seo_description',
                'visa_countries.country_name',
                'visa_countries.serviceability',
                'visa_countries.visa_type',
                'visa_countries.visa_kind',
                'visa_countries.visa_category',
                'visa_countries.visa_entry',
                'visa_countries.visa_department',
                'visa_countries.extra_details',
                'visa_countries.seo_title',
                'visa_countries.seo_description',
                'visa_countries.documents',
                'visa_countries.is_active',
                'visa_types.visa_type',
                'visa_kinds.visa_kind'
            ],
            [
                'visa_countries.country_name' => $countryName,
                'visa_countries.is_active' => 1
            ]
        );

        // Check if country details exist
        if (empty($countryDetails)) {
            throw new Exception("No country found with the specified name.");
        }

        $countryData = $countryDetails[0];

        // Fetch images if requested
        if ($includeImages) {
            $countryId = $countryData['country_id'];
            $countryImages = $database->select(
                'country_images',
                [
                    'fallback_url',
                    'photo_path'
                ],
                [
                    'country_id' => $countryId
                ]
            );

            $countryData['images'] = $countryImages;
        }

        return $countryData;
    } catch (Exception $e) {
        error_log($e->getMessage());
        return [];
    }
}



// Fetch country details
$countryData = fetchCountryDetails($country_name);

// print_r($countryData);


if (empty($countryData)) {
    header("Location: ../404");
    exit;
}

$visa_info = $database->select(
    "visa_pricing",
    [
        'visa_pricing.stay_duration',
        'visa_pricing.id',
        'visa_pricing.pricing_name',
        'visa_pricing.visa_validity',
        'visa_pricing.visa_processing_time',
        'visa_pricing.visa_approval_rate',
        'visa_pricing.vfs_service_fee',
        'visa_pricing.embassy_fee',
        'visa_pricing.visa_assistance_fee',
        'visa_pricing.total_pricing',
        'visa_pricing.single_entry_fee',
        'visa_pricing.multiple_entry_fee',
        'visa_pricing.double_entry_fee', // Added double_entry_fee
        'visa_pricing.visa_on_arrival_fee'
    ],
    [
        'visa_pricing.country_id' => $countryData['country_id'],
        'ORDER' => ['visa_pricing.id' => 'ASC']
    ]
);

foreach ($visa_info as $iinfo) {
    $visa_price = $iinfo['total_pricing'];
}


// SEO
require 'vendor/autoload.php';

use Melbahja\Seo\Schema;
use Melbahja\Seo\Schema\Thing;
use Melbahja\Seo\MetaTags;

$seoUrl = 'https://fayyaztravels.com/visa/country/apply-for-' . $slug;

$schema = new Schema(
    new Thing('Organization', [
        'url' => 'https://fayyaztravels.com/visa',
        'logo' => 'https://fayyaztravels.com/uploads/images/main-logo.png',
        'contactPoint' => new Thing('ContactPoint', [
            'telephone' => '+65 6235 2900',
            'contactType' => 'customer service'
        ])
    ])
);

// Default values for SEO content
$seoTitle = !empty($countryData['seo_title']) ? $countryData['seo_title'] : "$country_name Visa: Fees, Requirements and Fee";
$seoDescription = !empty($countryData['seo_description']) ? $countryData['seo_description'] : "Apply for $country_name visa hassle-free with Fayyaz Travels. We offer quick processing and expert assistance.";

// write schema for this web page
$webPageSchema = new Schema(
    new Thing('WebPage', [
        'url' => $url,
        'name' => $seoTitle,
        'description' => $seoDescription,
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
                'name' => $country_name,
                'url' => $seoUrl
            ])
        ]
    ])
);

// schema for product
$productSchema = new Schema(
    new Thing('Product', [
        'name' => $seoTitle,
        'description' => $seoDescription,
        'url' => $url,
        'image' => "$url/og/index.php?title=" . urlencode($seoTitle) .
            "&subtitle=" . urlencode($seoDescription) .
            "&gradient=berry_frost",
        'offers' => new Thing('Offer', [
            'availability' => 'https://schema.org/InStock',
            'priceCurrency' => 'SGD',
            'price' => $visa_price,
            'url' => $url,
        ])
    ])
);

$faqs = $database->select("faq", ["id", "faqQuestion", "faqAnswer"], [
    "faqCountry" => $countryData['id'],
    "is_active" => 1
]);

$faqSchema = new Schema(
    new Thing('FAQPage', [
        'url' => $url,
        'name' => $seoTitle . ' FAQ',
        'mainEntity' => array_map(function ($faq) {
            return new Thing('Question', [
                'name' => $faq['faqQuestion'],
                'acceptedAnswer' => new Thing('Answer', [
                    'text' => $faq['faqAnswer']
                ])
            ]);
        }, $faqs)
    ])
);

$metatags = new MetaTags();

$metatags
    ->title($seoTitle . ' — Fayyaz Travels')
    ->description($seoDescription)  // Now using the variable with fallback value
    ->meta('author', 'Fayyaz Travels')
    ->meta('robots', 'index, follow')
    ->image($url . '/og/index.php?title=' . urlencode($seoTitle) . '&subtitle=' . urlencode($seoDescription) . '&gradient=' . urlencode('berry_frost'))
    ->canonical('https://fayyaztravels.com/visa/country/apply-for-' . $slug);

// Convert both to strings and concatenate
$seo = $schema . "\n" . $webPageSchema . "\n" . $breadcrumbSchema . "\n" . $faqSchema . "\n" . $productSchema . "\n" . $metatags;
// SEO END

// Output HTML head and scripts
echo html_head(
    ($country_name ?? 'Visa') . ' Visa Online &mdash; Fees, Requirements & Application Process',
    null,
    true,
    ['assets/css/visa.css', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css'],
    true,
    false,
    $seo ?? null
);

// print_r($countryData);

// Redirect to 404 if country not found
if (empty($countryData)) {
    header("Location: ../404");
    exit;
}
?>

<?php
// Check if user is logged in, then fetch the latest unfinished order for the given country
if (isset($_SESSION['user_id'])) {
    $orders = $database->get('orders', ['is_finished', 'is_processing', 'order_id', 'is_archive'], [
        'ORDER' => ['id' => 'DESC'],
        'LIMIT' => 1,
        'AND' => [
            'order_user_id' => $_SESSION['user_id'],
            'is_finished' => 0,
            'country_id' => $countryData['id'],
        ]
    ]);
}
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

<!-- Photo Grids -->
<?php
// Fetch country data with images
$countryData = fetchCountryDetails($country_name, true);
$visaDetail = fetchCountryDetails($country_name);

// Check if images exist
if (!empty($countryData['images'])) {
    $images = array_map(fn($image) => ['photo_path' => 'admin/' . $image['photo_path']], $countryData['images']);
    $hasImages = true;
} else {
    $images = [['photo_path' => 'assets/images/fly-high.jpg']];
    $hasImages = false;
}
?>

<div class="container my-4">

    <div class="row">
        <div class="col-12">
            <!-- images country -->
            <?php
            function renderAlert($message, $url, $btnMsg)
            {
                echo '<p class="alert alert-warning mt-2 d-flex align-items-center justify-content-between">
                    <i class="bi bi-info-circle me-2"></i>
                    <span class="flex-grow-1">' . $message . '</span>
                    <a href="' . $url . '" class="btn btn-blue rounded-pill">' . $btnMsg . '<i class="bi bi-chevron-right"></i></a>
                </p>';
            }

            if (isset($orders)) {
                // First, check if the order is archived
                if (isset($orders['is_archive']) && $orders['is_archive'] == 1) {
                    renderAlert("Your application for {$country_name} visa is archived.", "applications", "My Applications");
                } elseif ((isset($orders['is_finished']) && $orders['is_finished'] == 1) ||
                    (isset($orders['is_processing']) && $orders['is_processing'] == 0)
                ) {
                    renderAlert("You've already applied for {$country_name} visa.", "application/{$orders['order_id']}/persona", "Complete Applications");
                }
                // If still processing, show processing message
                elseif (isset($orders['is_processing']) && $orders['is_processing'] == 1) {
                    renderAlert("We are processing your {$country_name} visa.", "applications", "My Applications");
                }
            }
            ?>
        </div>
    </div>

    <?php if (!$hasImages): ?>
        <!-- Full-Width Image When No Images Found -->
        <div class="row">
            <div class="col-12">
                <picture>
                    <img loading="lazy"
                        src="assets/images/fly-high.jpg"
                        class="rounded-3 img-fluid"
                        alt="Default Visa Image">
                </picture>
            </div>
        </div>
    <?php else: ?>
        <!-- Image Carousel for Mobile -->
        <div class="row">
            <div class="col-12 d-block d-lg-none">
                <div id="countryCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner rounded-3">
                        <?php foreach ($images as $index => $image): ?>
                            <div class="carousel-item <?= $index === 0 ? 'active' : ''; ?>">
                                <picture>
                                    <source srcset="image.php?image=<?= htmlspecialchars($image['photo_path']); ?>&width=auto&height=700&format=avif" type="image/avif">
                                    <source srcset="image.php?image=<?= htmlspecialchars($image['photo_path']); ?>&width=auto&height=700&format=webp" type="image/webp">
                                    <img loading="lazy"
                                        src="image.php?image=<?= htmlspecialchars($image['photo_path']); ?>&width=auto&height=700&format=jpeg"
                                        class="d-block w-100 carousel-image"
                                        alt="<?= htmlspecialchars($countryData['country_name']); ?> Visa Apply Online">
                                </picture>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop Grid -->
        <div class="row d-none d-lg-block">
            <div class="col-12">
                <section class="mb-3">
                    <div class="grid-container">
                        <?php foreach ($images as $index => $image): ?>
                            <div class="grid-item <?= $index === 0 ? 'large' : ''; ?>">
                                <picture>
                                    <source srcset="image.php?image=<?= htmlspecialchars($image['photo_path']); ?>&width=auto&height=650&format=avif" type="image/avif">
                                    <source srcset="image.php?image=<?= htmlspecialchars($image['photo_path']); ?>&width=auto&height=650&format=webp" type="image/webp">
                                    <img loading="lazy"
                                        src="image.php?image=<?= htmlspecialchars($image['photo_path']); ?>&width=auto&height=650&format=jpeg"
                                        alt="<?= htmlspecialchars($countryData['country_name']); ?> Visa Apply Online" />
                                </picture>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            </div>
        </div>
    <?php endif; ?>
</div>




<!-- Visa Details -->
<section class="container mt-2">
    <div class="row">
        <div class="col-12">
            <div class="visa-header-section">
                <div class="visa-title-container">
                    <h1 class="visa-title">
                        <span class="country-name"><?= htmlspecialchars($country_name); ?></span>
                        <span class="visa-label">Visa</span>
                    </h1>
                </div>

                <div class="visa-metrics-container">
                    <div class="visa-metric d-flex align-items-center">
                        <div class="metric-icon me-2"><i class="bi bi-file-earmark-text"></i></div>
                        <div class="metric-data">
                            <?php
                            // no of applications processed
                            $hash = crc32($country_name);
                            $issuedCount = 10 + ($hash % 50);
                            ?>
                            <span class="metric-value fw-bold"><?= $issuedCount; ?></span>
                            <span class="metric-label text-muted">applications processed</span>
                        </div>
                    </div>
                    <div class="visa-metric trending-metric">
                        <div class="metric-icon"><i class="bi bi-arrow-up-right-circle"></i></div>
                        <div class="metric-data">
                            <span class="metric-value">Trending</span>
                            <span class="metric-label">destination</span>
                        </div>
                    </div>
                    <div class="visa-metric">
                        <div class="metric-icon"><i class="bi bi-eye"></i></div>
                        <div class="metric-data">
                            <span class="metric-value">200</span>
                            <span class="metric-label">views this week</span>
                        </div>
                    </div>
                    <div class="visa-metric">
                        <div class="metric-icon"><i class="bi bi-fire"></i></div>
                        <div class="metric-data">
                            <span class="metric-value">Peak Season Approaching</span>
                            <span class="metric-label">Apply Now</span>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                .visa-header-section {
                    border-radius: 8px;
                    margin-bottom: 1.5rem;
                }

                .visa-title-container {
                    margin-bottom: 1rem;
                    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                    padding-bottom: 0.75rem;
                }

                .visa-title {
                    font-size: 2.2rem;
                    font-weight: 700;
                    color: var(--blue);
                    margin: 0;
                    display: flex;
                    align-items: center;
                    flex-wrap: wrap;
                }

                .country-name {
                    margin-right: 0.5rem;
                }

                .visa-label {
                    color: var(--blue-light);
                }

                .visa-metrics-container {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 1rem;
                    justify-content: space-between;
                }

                .visa-metric {
                    display: flex;
                    align-items: center;
                    padding: 0.5rem 1rem;
                    background-color: white;
                    border-radius: 6px;
                    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
                    flex: 1;
                    min-width: 180px;
                    transition: transform 0.2s, box-shadow 0.2s;
                }

                .visa-metric:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.06);
                }

                .trending-metric {
                    background-color: #e8f4ff;
                    border-left: 3px solid var(--blue-light);
                }

                .metric-icon {
                    background-color: #f8f9fa;
                    width: 36px;
                    height: 36px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin-right: 0.75rem;
                    color: var(--blue);
                    font-size: 1.1rem;
                }

                .trending-metric .metric-icon {
                    color: var(--blue-light);
                    background-color: rgba(58, 110, 143, 0.1);
                }

                .metric-data {
                    display: flex;
                    flex-direction: column;
                }

                .metric-value {
                    font-weight: 700;
                    font-size: 1rem;
                    color: var(--blue-dark);
                }

                .metric-label {
                    font-size: 0.8rem;
                    color: var(--blue-lighter);
                }

                @media (max-width: 768px) {
                    .visa-metrics-container {
                        flex-direction: column;
                        gap: 0.75rem;
                    }

                    .visa-metric {
                        width: 100%;
                    }

                    .visa-title {
                        font-size: 1.8rem;
                    }
                }
            </style>
        </div>
    </div>

    <div class="row d-flex flex-column-reverse flex-xl-row">
        <div class="col-12 col-xl-8 col-xxl-8">

            <!-- content area -->
            <!-- <h2 class="heading-underline fw-bold"></?= $country_name; ?> Visa Information</h2> -->
            <!-- grid visa details -->
            <!-- <div class="row">
                <div class="col-12">


                </div>
            </div> -->
            <!-- ./visa grid details -->



            <div class="visa-container">
                <div class="card visa-card-box">
                    <div class="visa-card-header">
                        <h5 class="visa-title-text mt-2"><i class="bi bi-info-circle"></i> Visa Information</h5>
                    </div>

                    <div class="visa-info-grid">
                        <!-- Visa Type -->
                        <div class="visa-info-block">
                            <div class="visa-icon-box">
                                <div class="visa-icon-circle" style="background-color: #14385C;">
                                    <i class="bi bi-passport"></i>
                                </div>
                            </div>
                            <div class="visa-label-text">Visa Type</div>
                            <div class="visa-value-text"><?= $visaDetail['visa_type'] ?? 'E-Visa'; ?></div>
                        </div>

                        <!-- Length of Stay -->
                        <div class="visa-info-block">
                            <div class="visa-icon-box">
                                <div class="visa-icon-circle" style="background-color: #14385C;">
                                    <i class="bi bi-calendar2-week"></i>
                                </div>
                            </div>
                            <div class="visa-label-text">Length of Stay</div>
                            <div class="visa-value-text">
                                <?php
                                $stayDurations = array_map(fn($info) => $info['stay_duration'] . ' days', $visa_info);
                                $uniqueStayDurations = array_unique($stayDurations);
                                $count = count($uniqueStayDurations);
                                if ($count > 2) {
                                    echo implode(', ', array_slice($uniqueStayDurations, 0, -1)) . ' or ' . end($uniqueStayDurations);
                                } elseif ($count === 2) {
                                    echo implode(' or ', $uniqueStayDurations);
                                } elseif ($count === 1) {
                                    echo $uniqueStayDurations[0];
                                }
                                ?>
                            </div>
                            <div class="visa-description-text">
                                Depends on the discretion of the immigration officer upon arrival.
                            </div>
                        </div>

                        <!-- Validity -->
                        <div class="visa-info-block">
                            <div class="visa-icon-box">
                                <div class="visa-icon-circle" style="background-color: #14385C;">
                                    <i class="bi bi-check2-circle"></i>
                                </div>
                            </div>
                            <div class="visa-label-text">Validity</div>
                            <div class="visa-value-text">
                                <?php
                                $validities = array_map(fn($info) => $info['visa_validity'] . " days", $visa_info);
                                $uniqueValidities = array_unique($validities);
                                $count = count($uniqueValidities);
                                if ($count > 2) {
                                    echo implode(', ', array_slice($uniqueValidities, 0, -1)) . ' or ' . end($uniqueValidities);
                                } elseif ($count === 2) {
                                    echo implode(' or ', $uniqueValidities);
                                } elseif ($count === 1) {
                                    echo $uniqueValidities[0];
                                }
                                ?>
                            </div>
                            <div class="visa-description-text">
                                Depends on the discretion of the embassy.

                            </div>
                        </div>

                        <!-- Entry -->
                        <div class="visa-info-block">
                            <div class="visa-icon-box">
                                <div class="visa-icon-circle" style="background-color: #14385C;">
                                    <i class="bi bi-door-open"></i>
                                </div>
                            </div>
                            <div class="visa-label-text">Entry</div>
                            <div class="visa-value-text">
                                <?php
                                $entry_string = $database->get("visa_countries", "visa_entry", ["country_name" => $country_name]);

                                if (!empty($entry_string)) {
                                    $entry_array = explode(',', $entry_string);
                                    $formatted_entries = implode(' or ', array_map('ucfirst', $entry_array));
                                    echo $formatted_entries;
                                } else {
                                    echo "Not available";
                                }
                                ?>
                            </div>
                            <div class="visa-description-text">
                                Depends on the discretion of the embassy.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- visa processing time -->

            <h2 class="heading-underline fw-bold mt-3"> <i class="bi bi-hourglass-split"></i> Visa Processing Time</h2>
            <p>The visa Processing Time for <?= $country_name; ?> is
                <?php
                $count = count($visa_info);
                $i = 0;
                foreach ($visa_info as $info):
                    $i++;
                    echo '<b>' . $info['visa_processing_time']  . " working days for " . '</b>' . $info['pricing_name'];

                    if ($i < $count - 1) {
                        echo ", ";
                    } elseif ($i == $count - 1) {
                        echo " and ";
                    }
                endforeach;
                ?>
            </p>

            <h2 class="heading-underline fw-bold mt-3">
                <i class="bi bi-info-circle me-2"></i>Note
            </h2>
            <p><?= $countryData['extra_details']; ?></p>

            <h2 class="heading-underline fw-bold mt-3">
                <i class="bi bi-file-earmark-text me-2"></i>Required Documents
            </h2>
            <?php
            // Fetch the required documents JSON for the given country
            $countryInfo = $database->get("visa_countries", "documents", ["id" => $countryData['id'], "is_active" => 1]);

            // Decode JSON to get an array of document IDs and their requirement types
            $documentData = json_decode($countryInfo, true);

            $mandatoryDocs = [];
            $optionalDocs = [];

            if (!empty($documentData) && is_array($documentData)) {
                // Extract document IDs
                $documentIds = array_column($documentData, 'id');

                if (!empty($documentIds)) {
                    // Fetch document names based on IDs
                    $documents = $database->select("required_documents", ["id", "required_document_name"], ["id" => $documentIds]);

                    // Create an associative array with document IDs as keys for quick lookup
                    $requirementTypes = array_column($documentData, 'type', 'id'); // FIXED: Using 'type' instead of 'requirementType'

                    // Separate documents into mandatory and optional
                    foreach ($documents as $document) {
                        $requirement = $requirementTypes[$document['id']] ?? 'unknown';

                        if ($requirement === 'mandatory') {
                            $mandatoryDocs[] = $document;
                        } elseif ($requirement === 'optional') {
                            $optionalDocs[] = $document;
                        }
                    }
                }
            }

            if (!empty($mandatoryDocs) || !empty($optionalDocs)): ?>
                <ul class="list-group">
                    <li class="list-group-item" style="background-color: var(--blue); color: white; border: 1px solid var(--blue);">
                        <p class="mb-0"><b><?= countryToEmoji($country_name); ?> Required documents for <?= htmlspecialchars($country_name); ?> Visa</b></p>
                    </li>

                    <?php if (!empty($mandatoryDocs)): ?>
                        <?php foreach ($mandatoryDocs as $index => $document): ?>
                            <li class="list-group-item"
                                style="border: 1px solid var(--blue); 
                   <?= ($index === array_key_last($mandatoryDocs) && !empty($optionalDocs)) ? 'border-bottom: 0;' : 'border-bottom: none;'; ?>">
                                <?= htmlspecialchars($document['required_document_name']); ?>
                                <span class="badge bg-danger rounded-pill float-end">Mandatory</span>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if (!empty($optionalDocs)): ?>
                        <?php foreach ($optionalDocs as $index => $document): ?>
                            <li class="list-group-item"
                                style="border: 1px solid var(--blue); 
                   <?= ($index === array_key_last($optionalDocs)) ? 'border-bottom: 1px solid var(--blue);' : 'border-bottom: none;'; ?>">
                                <?= htmlspecialchars($document['required_document_name']); ?>
                                <span class="badge bg-secondary rounded-pill float-end">Optional</span>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>


                </ul>
            <?php else: ?>
                <p>No documents required for <?= htmlspecialchars($countryData['country_name']); ?>.</p>
            <?php endif; ?>

            <h2 class="heading-underline fw-bold mt-3">
                <i class="bi bi-question-circle"></i> Frequently Asked Questions
            </h2>

            <?php
            // Fetch the active FAQs for the selected country
            $faqs = $database->select("faq", ["id", "faqQuestion", "faqAnswer"], [
                "faqCountry" => $countryData['id'],
                "is_active" => 1
            ]); ?>

            <?php if (!empty($faqs)): ?>
                <div class="accordion" id="faqAccordion">
                    <?php foreach ($faqs as $index => $faq): ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading<?= $faq['id']; ?>">
                                <button class="accordion-button fw-bold <?= $index === 0 ? '' : 'collapsed'; ?>" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse<?= $faq['id']; ?>"
                                    aria-expanded="<?= $index === 0 ? 'true' : 'false'; ?>"
                                    aria-controls="collapse<?= $faq['id']; ?>">
                                    <?= htmlspecialchars($faq['faqQuestion']); ?>
                                </button>
                            </h2>
                            <div id="collapse<?= $faq['id']; ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : ''; ?>"
                                aria-labelledby="heading<?= $faq['id']; ?>" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <?= nl2br(htmlspecialchars($faq['faqAnswer'])); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No FAQs available for this country.</p>
            <?php endif; ?>
            <!-- <p class="my-2 fw-bold text-brown">Got more questions about a </?= $country_name; ?> visa? Chat with our visa specialist bot, <a href="../mybot">Lai Lai</a>!</p> -->

            <!--<div>
                <h2 class="heading-underline fw-bold mt-3">Rate Our Service</h2>
                <p class="text-muted">Your rating helps others better understand your experience with us.</p>
                //Star Rating
                <div id="star-container">
                    <i class="bi bi-star stars" data-value="1"></i>
                    <i class="bi bi-star stars" data-value="2"></i>
                    <i class="bi bi-star stars" data-value="3"></i>
                    <i class="bi bi-star stars" data-value="4"></i>
                    <i class="bi bi-star stars" data-value="5"></i>
                </div>
                // Star Rating
                <div class="card card-rating p-4 mt-3 rounded-3">
                    // Overall Rating
                    <div class="d-flex align-items-center">
                        <h2 class="mb-0 display-5 fw-bold">4.0</h2>
                        <i class="bi bi-star-fill text-warning fs-3 ms-2"></i>
                    </div>
                    <p class="text-muted mb-3">67,817 ratings and 13,302 reviews</p>

                    // Rating Breakdown
                    <div id="ratings-container"></div>
                </div>
                // Ratings 
                // Review Modal
                <div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Write a Review</h5>
                                <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center">
                                <p id="thankYouMessage" class="fw-bold text-success"></p>
                                <textarea
                                    id="reviewText"
                                    class="form-control"
                                    rows="4"
                                    placeholder="Write your review here..."></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-golden rounded-pill" id="submitReview">
                                    Submit Review <i class="bi bi-arrow-right-circle ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                // Logout Alert Modal
                <div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Login Required</h5>
                                <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form id="loginForm" autocomplete="off" novalidate>
                                    <div class="mb-3">
                                        <label for="exampleInputEmail1" class="form-label">Email address</label>
                                        <input type="email" class="form-control" id="exampleInputEmail1" required>
                                        <div class="invalid-feedback">Please enter a valid email.</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="exampleInputPassword1" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="exampleInputPassword1" required>
                                        <div class="invalid-feedback">Password is required.</div>
                                    </div>
                                    <button type="submit" class="btn btn-golden rounded-pill">Log in <i class="bi bi-arrow-right-circle"></i></button>
                                    <div id="loginMessage" class="mt-3"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <h2 class="heading-underline fw-bold mt-3">Review (0)</h2>
                <p class="text-muted">Your feedback helps us improve and ensures others receive reliable visa assistance.</p>
                // Review Form
                <form action="">
                    <div class="editor-container">
                        <div class="editor-toolbar">
                            <div class="btn-group" role="group" aria-label="Text formatting">
                                <button type="button" class="btn btn-secondary border-0 bg-gradient" data-bs-toggle="tooltip" data-bs-placement="top" title="Bold (Ctrl + B)" onclick="formatText('bold')">
                                    <b>B</b>
                                </button>
                                <button type="button" class="btn btn-secondary border-0 bg-gradient" data-bs-toggle="tooltip" data-bs-placement="top" title="Italic (Ctrl + I)" onclick="formatText('italic')">
                                    <i>I</i>
                                </button>
                                <button type="button" class="btn btn-secondary  border-0 bg-gradient" data-bs-toggle="tooltip" data-bs-placement="top" title="Underline (Ctrl + U)" onclick="formatText('underline')">
                                    <u>U</u>
                                </button>
                                <button type="button" class="btn btn-secondary bg-gradient border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Numbered List (Ctrl + L)" onclick="formatText('insertOrderedList')">
                                    <i class="bi bi-list-ol"></i>
                                </button>
                                <button type="button" class="btn btn-secondary border-0 bg-gradient" data-bs-toggle="tooltip" data-bs-placement="top" title="Bullet List (Ctrl + M)" onclick="formatText('insertUnorderedList')">
                                    <i class="bi bi-list-ul"></i>
                                </button>
                                // Help Button
                                <button type="button" class="btn btn-secondary border-0 bg-gradient" data-bs-toggle="modal" data-bs-target="#shortcutsModal">
                                    <i class="bi bi-question-circle"></i>
                                </button>
                            </div>
                        </div>

                        // Editable Text Area
                        <div contenteditable="true" id="review" class="editor-content"></div>

                        // Hidden input to store the formatted content
                        <input type="hidden" name="reviewContent" id="reviewContent">

                        // Submit Button
                    </div>
                    <button type="submit" class="btn btn-golden mt-2 rounded-pill" onclick="submitReview()">Post <i class="bi bi-arrow-right-circle"></i></button>
                </form>
                // ./Review Form
            </div>

            // Bootstrap Modal for Shortcuts 
            <div class="modal fade" id="shortcutsModal" tabindex="-1" aria-labelledby="shortcutsModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="shortcutsModalLabel"> <i class="bi bi-keyboard"></i>
                                Keyboard Shortcuts</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <ul class="list-group">
                                <li class="list-group-item"><b>Ctrl + B</b>: Bold</li>
                                <li class="list-group-item"><b>Ctrl + I</b>: Italic</li>
                                <li class="list-group-item"><b>Ctrl + U</b>: Underline</li>
                                <li class="list-group-item"><b>Ctrl + L</b>: Numbered List</li>
                                <li class="list-group-item"><b>Ctrl + M</b>: Bullet List</li>
                            </ul>

                        </div>
                    </div>
                </div>
            </div> -->


            <!-- <h2 class="heading-underline fw-bold mt-3">Other Popular Countries</h2>
            <p>Users who looked for </?= $country_name; ?>, also looked at these countries</p> -->




            <!-- ./content area -->
        </div>
        <div class="col-12 col-xl-4 col-xxl-4 mb-3">

            <!-- visa details -->
            <h2 class="heading-underline fw-bold mt-3">
                <i class="bi bi-card-text me-2"></i> Visa Options
            </h2>


            <?php foreach ($visa_info as $info): ?>
                <div class="card mb-2 bg-gradient" style="border: 1px solid var(--blue);">
                    <div class="card-header text-white" style="background-color: var(--blue); border-bottom: 1px solid var(--blue);">
                        <b><?= $visaDetail['country_name']; ?> <?= $visaDetail['visa_kind']; ?></b>
                        <p class="card-text fw-bold">
                            <?= $info['pricing_name']; ?>
                            <br>
                            <a href="start-application?pid=<?= $info['id']; ?>&cid=<?= $countryData['country_id']; ?>" class="btn rounded-pill bg-gradient mt-2" style="background-color: var(--golden-light);">Start Application <i class="bi bi-arrow-up-right-circle"></i></a>
                        </p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item" style="border-bottom: 1px solid var(--blue);">Visa Type <span class="float-end text-muted"><?= $visaDetail['visa_type']; ?></span></li>
                        <li class="list-group-item" style="border-bottom: 1px solid var(--blue);">Stay Duration <span class="float-end text-muted"><?= $info['stay_duration']; ?> Days</span></li>
                        <li class="list-group-item" style="border-bottom: 1px solid var(--blue);">Visa Validity <span class="float-end text-muted"><?= $info['visa_validity']; ?> Days</span></li>
                        <li class="list-group-item" style="border-bottom: 1px solid var(--blue);">Processing Time <span class="float-end text-muted"><?= $info['visa_processing_time']; ?> Working Days</span></li>
                        <?php if ($info['pricing_name']): ?>
                            <?php $checkString = $info['pricing_name']; ?>
                            <?php if (preg_match('/\b(Single Entry|Visa on Arrival|Multiple Entry)\b/i', $checkString, $match)) : ?>
                                <li class="list-group-item" style="border-bottom: 1px solid var(--blue);">
                                    Entry Type <span class="float-end text-muted"><?= $match[1]; ?></span>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>

                        <li class="list-group-item fees-modal-trigger">
                            <b>Total Amount</b> <span class="float-end text-golden">
                                <i class="bi bi-info-circle me-1" tabindex="0" role="button" data-bs-toggle="popover"
                                    data-bs-html="true" data-bs-trigger="hover focus" title="Fee Breakdown" data-bs-placement="left"
                                    data-bs-content="
                                            <div class='list-group text-end p-0' style='margin: 0;'>
                                                <?php
                                                $fees = [
                                                    'Embassy Fee' => $info['embassy_fee'],
                                                    'Admin Fee' => $info['vfs_service_fee'],
                                                    'Our Fee' => $info['visa_assistance_fee']
                                                ];

                                                if (!empty($info['single_entry_fee']) && $info['single_entry_fee'] > 0.00) {
                                                    $fees['Single Entry Fee'] = $info['single_entry_fee'];
                                                }

                                                if (!empty($info['multiple_entry_fee']) && $info['multiple_entry_fee'] > 0.00) {
                                                    $fees['Multiple Entry Fee'] = $info['multiple_entry_fee'];
                                                }

                                                if (!empty($info['double_entry_fee']) && $info['double_entry_fee'] > 0.00) { // Added double_entry_fee
                                                    $fees['Double Entry Fee'] = $info['double_entry_fee'];
                                                }

                                                if (!empty($info['visa_on_arrival_fee']) && $info['visa_on_arrival_fee'] > 0.00) {
                                                    $fees['Visa on Arrival Fee'] = $info['visa_on_arrival_fee'];
                                                }

                                                foreach ($fees as $key => $amount) {
                                                    echo "<div class='list-group-item border-0 d-flex justify-content-between'>
                                                            <span class='small'>$key</span> <strong class='small'>S$$amount</strong>
                                                        </div>";
                                                }
                                                ?>
                                            </div>">
                                </i>
                                <b>S$<?=
                                        ($info['embassy_fee'] ?? 0) +
                                            ($info['vfs_service_fee'] ?? 0) +
                                            ($info['visa_assistance_fee'] ?? 0) +
                                            ($info['single_entry_fee'] ?? 0) +
                                            ($info['multiple_entry_fee'] ?? 0) +
                                            ($info['double_entry_fee'] ?? 0) + // Added double_entry_fee
                                            ($info['visa_on_arrival_fee'] ?? 0);
                                        ?></b>
                            </span>
                        </li>
                    </ul>
                </div>
            <?php endforeach; ?>
            <?php if (
                isset($orders) && (
                    (isset($orders['is_finished']) && $orders['is_finished'] == 1) ||
                    (isset($orders['is_processing']) && $orders['is_processing'] == 1) ||
                    (isset($orders['is_processing']) && $orders['is_processing'] == 0) ||
                    (isset($orders['is_archive']) && $orders['is_archive'] == 1)  // Check if archived first
                )
            ): ?>
                <!-- Calculator is hidden -->
            <?php else: ?>
                </?php require 'components/Calculator.php' ; ?>
            <?php endif; ?>

            <!-- ./visa calculator -->
        </div>
    </div>
</section>
<!-- ./Visa Details -->


<!-- Modal -->
<div class="modal fade" id="travellerLimitModal" tabindex="-1" aria-labelledby="travellerLimitModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header text-white" style="background-color: var(--golden);">
                <h5 class="modal-title" id="travellerLimitModalLabel">Traveller Limit Exceeded</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-4">You can only select up to 6 travellers.</p>

                <form action="/submit" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control form-control-lg" id="name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control form-control-lg" id="email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="mobile" class="form-label">Mobile Number</label>
                        <div class="input-group">
                            <span class="input-group-text" id="countryCode">+1</span>
                            <input type="tel" class="form-control form-control-lg" id="mobile" name="mobile" required>
                        </div>
                    </div>

                    <!-- Hidden field for the current page name -->
                    <input type="hidden" id="currentPage" name="currentPage">

                    <!-- Optionally, you can use JavaScript to get the user's IP address (if required for your use case) -->
                    <input type="hidden" id="userIP" name="userIP">
                    <input type="hidden" id="stateName" name="stateName">
                    <input type="hidden" id="cityName" name="cityName">
                    <input type="hidden" id="countryName" name="countryName">

                    <button type="submit" class="btn btn-golden btn-lg w-100 mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Fee Modal -->
<div class="modal fade" id="feesModal" tabindex="-1" aria-labelledby="feesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="feesModalLabel">Fees Breakdown</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="feesModalContent"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Footer -->
<?php require 'components/Footer.php'; ?>

<?php
// Output HTML scripts
echo html_scripts(
    includeJQuery: true,
    includeBootstrap: true,
    customScripts: ['https://cdn.jsdelivr.net/npm/flatpickr'],
    includeSwal: false,
    includeNotiflix: true
);
?>
<!-- Bootstrap Popover Initialization -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var popoverTriggerList = document.querySelectorAll('.popover-trigger');

        popoverTriggerList.forEach(function(popoverTriggerEl) {
            var popover = new bootstrap.Popover(popoverTriggerEl);

            // Hide popover on click outside
            document.addEventListener("click", function(event) {
                if (!popoverTriggerEl.contains(event.target)) {
                    popover.hide();
                }
            });
        });
    });
</script>

<script>
    // Dynamically injecting PHP values into JavaScript variables
    const embassyFeePerTraveller = <?= (float) ($visaDetail['embassy_fee'] ?? 0); ?>;
    const portifyFees = <?= isset($visaDetail['portify_fees']) ? (float) $visaDetail['portify_fees'] : 0; ?>;
    const vfsFees = <?= isset($visaDetail['vfs_service_fees']) ? (float) $visaDetail['vfs_service_fees'] : 0; ?>;
    const ourFeePerTraveller = portifyFees + vfsFees;
    const applyButton = document.querySelector('#applyButton');

    let travellerCount = 1;

    const updateTravellers = (amount) => {
        travellerCount = Math.min(6, Math.max(1, travellerCount + amount)); // Ensure between 1 and 6 travellers
        if (travellerCount === 6 && amount > 0) showModal(); // Show modal if count exceeds 6
        updateUI();
    };

    const updateUI = () => {
        document.getElementById('travellerCount').textContent = travellerCount;
        document.getElementById('travellerInput').value = travellerCount;
        document.getElementById('embassyFeeMultiplier').textContent = travellerCount;

        // Only update the fee multipliers if they exist in the DOM
        if (document.getElementById('ourFeeMultiplier')) {
            document.getElementById('ourFeeMultiplier').textContent = travellerCount;
        }
        if (document.getElementById('vfsFeeMultiplier')) {
            document.getElementById('vfsFeeMultiplier').textContent = travellerCount;
        }

        document.getElementById('totalAmount').textContent = formatCurrency((embassyFeePerTraveller + ourFeePerTraveller) * travellerCount);

        applyButton.disabled = travellerCount > 5;
    };

    const showModal = () => {
        // Use Bootstrap's modal functionality to show the modal
        new bootstrap.Modal(document.getElementById('travellerLimitModal')).show();
    };

    const formatCurrency = (amount) => {
        return new Intl.NumberFormat('en-SG', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(amount);
    };
</script>

<!-- 
<script>
    // Dynamically injecting PHP values into JavaScript variables
    const embassyFeePerTraveller = </?= (float) $visaDetail['embassy_fee']; ?>;
    const ourFeePerTraveller = </?= (float) $visaDetail['portify_fees'] + (float) $visaDetail['vfs_service_fees']; ?>;
    const applyButton = document.querySelector('#applyButton');

    let travellerCount = 1;

    const updateTravellers = (amount) => {
        travellerCount = Math.min(6, Math.max(1, travellerCount + amount)); // Ensure between 1 and 6 travellers
        if (travellerCount === 6 && amount > 0) showModal(); // Show modal if count exceeds 6
        updateUI();
    };

    const updateUI = () => {
        document.getElementById('travellerCount').textContent = travellerCount;
        document.getElementById('travellerInput').value = travellerCount;
        document.getElementById('embassyFeeMultiplier').textContent = travellerCount;
        document.getElementById('ourFeeMultiplier').textContent = travellerCount;
        document.getElementById('vfsFeeMultiplier').textContent = travellerCount;
        document.getElementById('totalAmount').textContent = formatCurrency((embassyFeePerTraveller + ourFeePerTraveller) * travellerCount);

        if (travellerCount > 5) {
            applyButton.disabled = true;
        } else {
            applyButton.disabled = false;
        }
    };
    const showModal = () => {
        // Use Bootstrap's modal functionality to show the modal
        new bootstrap.Modal(document.getElementById('travellerLimitModal')).show();
    };

    const formatCurrency = (amount) => {
        return new Intl.NumberFormat('en-SG').format(amount);
    };
</script>
 -->

<!-- Script to Handle Date Blocking -->

<!-- <script>
    document.getElementById('loginForm').addEventListener('submit', async function(event) {
        event.preventDefault();
        event.stopPropagation();

        const form = this;
        form.classList.add('was-validated');

        if (!form.checkValidity()) return;

        const email = document.getElementById('exampleInputEmail1').value;
        const password = document.getElementById('exampleInputPassword1').value;
        const loginMessage = document.getElementById('loginMessage');

        loginMessage.innerHTML = "Logging in...";

        try {
            const response = await fetch('api/v1/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'HU': '</?= $hu; ?>'
                },
                body: JSON.stringify({
                    email,
                    password
                })
            });

            const data = await response.json();

            if (response.ok) {
                // loginMessage.innerHTML = `<div class="alert alert-success">Login successful! Welcome back.</div>`;
                Notiflix.Notify.success('Login successful! Welcome back.');
                setTimeout(() => {
                    location.reload(); // Reload the same page after successful login
                }, 1500);
            } else {
                Notiflix.Notify.failure(`${data.error}`);
                // loginMessage.innerHTML = `<div class="alert alert-danger">}</div>`;
            }
        } catch (error) {
            // loginMessage.innerHTML = `<div class="alert alert-danger">An error occurred. Please try again.</div>`;
            Notiflix.Notify.failure('An error occurred. Please try again.');

        }
    });
</script> -->


<script>
    document.addEventListener("DOMContentLoaded", function() {
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    });
</script>



<script>
    var visitCount = <?php echo getVisitCount(); ?>;

    if (visitCount > 5) {
        document.querySelector('.visa-metric').style.display = 'block';
    } else {
        document.querySelector('.visa-metric').style.display = 'none';
    }
</script>







</body>

</html>