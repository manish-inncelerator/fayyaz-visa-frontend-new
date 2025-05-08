<?php

defined('BASE_DIR') || die('Direct access denied');

// Enable error reporting (Remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include required files
require 'inc/html_head.php';
require 'inc/html_foot.php';
require 'database.php';
require 'min.php';

// Sanitize input parameters
// Sanitize input parameters
$order_id = isset($params['order_id']) ? htmlspecialchars($params['order_id'], ENT_QUOTES, 'UTF-8') : null;
$currentStep = isset($params['step']) ? htmlspecialchars($params['step'], ENT_QUOTES, 'UTF-8') : null;
$through = filter_input(INPUT_GET, 'through', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';

// Redirect to 404 if no order ID is provided
if (!$order_id) {
    header("location:/visa_f/404");
    exit();
}

// Redirect to login if user is not authenticated
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../auth/login?goto=' . urlencode($order_id));
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    // Update order if 'through' is set and user is logged in
    if (!empty($through) && !empty($user_id)) {
        $updateOrder = $database->update("orders", [
            "order_user_id" => $user_id
        ], [
            "order_id" => $order_id
        ]);
    }

    // Fetch the order details for the logged-in user
    $getOrder = $database->get("orders", ["order_id", "country_id"], [
        "AND" => [
            "order_user_id" => $user_id,
            "order_id"      => $order_id
        ]
    ]);

    // Check if the order exists
    if (!$getOrder) {
        // header("location:/visa_f/404");
        exit();
    }

    // Fetch visa passenger details
    $visaPax = $database->get('orders', ['no_of_pax'], ['order_id' => $order_id]);

    // Fetch country name safely
    $countryName = $database->get('visa_countries', ['country_name'], ['country_id' => $getOrder['country_id'] ?? null]);
} catch (Exception $e) {
    error_log("Database Error: " . $e->getMessage()); // Log instead of die
    header("location:/visa_f/error"); // Redirect to error page
    exit();
}

// Output HTML head
echo html_head('Applying for Visa', null, true, ['assets/css/fillForm.css', 'assets/css/application.css'], true);
?>


<!-- Navbar -->
<?php require 'components/ApplicationNavbar.php'; ?>
<!-- ./Navbar -->

<!-- Upload Section -->
<section class="container-fluid">
    <div class="row">
        <div class="col-12 col-lg-12 col-xl-3 col-xxl-3">
            <?php require 'components/UploadStep.php'; ?>
        </div>
        <div class="col-12 col-lg-12 col-xl-6 col-xxl-6">
            <?php if ($currentStep === 'persona') {
                include 'components/steps/Persona.php';
            } elseif ($currentStep === 'photo') {
                include 'components/steps/Photo.php';
            } elseif ($currentStep === 'passport') {
                include 'components/steps/Passport.php';
            } elseif ($currentStep === 'other documents') {
                include 'components/steps/otherDocuments.php';
            } elseif ($currentStep === 'details') {
                include 'components/steps/DeepSeek.php';
            } elseif ($currentStep === 'checkout') {
                include 'components/steps/Checkout.php';
            } else {
                echo ' <h1 class="fw-bold text-golden"><b>404</b></h1><p>Oops! Oh no! Where did it go?<br>  
                    This page is lost, that much we know.<br>  
                    Maybe it ran, maybe it hid,<br>  
                    Or maybe it never even did! 🤔</p>  

                    <p>But don\'t you fret, don\'t you cry,<br>  
                    Just hit <a href="/visa/">home</a> and give it a try!<br>  
                    The internet\'s wild, links go astray,<br>  
                    But we\'ll get you back on track - hurray! 🎉</p>';
            } ?>
        </div>
        <div class="col-12 col-lg-12 col-xl-3 col-xxl-3">
            <?php if ($currentStep === 'persona') {
                include 'components/sidebar/Persona.php';
            } elseif ($currentStep === 'photo') {
                include 'components/sidebar/Photo.php';
            } elseif ($currentStep === 'passport') {
                include 'components/sidebar/Passport.php';
            } ?>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header text-white" style="background-color: var(--golden);">
                <h5 class="modal-title fw-semibold" id="qrModalLabel">
                    Scan QR to
                    <?php echo ($currentStep != 'photo') ? 'fill up the form' : 'upload snap'; ?>
                    using your phone
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body py-4">
                <div class="container">
                    <div class="row justify-content-center text-center">
                        <!-- QR Code Centered -->
                        <div class="col-12 mb-4">
                            <p class="mt-1 fw-semibold text-muted mb-0">Scan QR code to continue</p>
                            <div class="p-1 rounded-3 mx-auto" style="max-width: 280px;">
                                <canvas id="qrCode" style="max-width: 100%; height: auto;"></canvas>
                            </div>
                        </div>

                        <!-- Instructions -->
                        <div class="col-12">
                            <h5 class="fw-bold mb-3 text-left">How to Scan?</h5>
                            <ul class="list-group shadow-sm rounded-4 overflow-hidden">
                                <li class="list-group-item d-flex align-items-center gap-3">
                                    <span class="bg-primary text-white p-2 rounded-circle d-flex justify-content-center align-items-center" style="width: 45px; height: 45px;">
                                        <i class="bi bi-camera" style="font-size: 1.5rem;"></i>
                                    </span>
                                    <div>Open your <strong>phone's camera</strong></div>
                                </li>
                                <li class="list-group-item d-flex align-items-center gap-3">
                                    <span class="bg-success text-white p-2 rounded-circle d-flex justify-content-center align-items-center" style="width: 45px; height: 45px;">
                                        <i class="bi bi-qr-code-scan" style="font-size: 1.5rem;"></i>
                                    </span>
                                    <div><strong>Scan</strong> the QR code</div>
                                </li>
                                <li class="list-group-item d-flex align-items-center gap-3">
                                    <span class="bg-danger text-white p-2 rounded-circle d-flex justify-content-center align-items-center" style="width: 45px; height: 45px;">
                                        <i class="bi bi-link-45deg" style="font-size: 1.5rem;"></i>
                                    </span>
                                    <div><strong>Tap</strong> on the link that appears</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light d-flex justify-content-center">
                <button type="button" class="btn btn-outline-blue px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php require 'components/Footer.php'; ?>
<?php
// Determine custom scripts based on the current step
$customScripts_normal = [
    'https://cdn.jsdelivr.net/npm/avalynx-select@0.0.1/dist/js/avalynx-select.js',
    'https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js'
];


$customScripts_photo = [
    'https://cdn.jsdelivr.net/npm/avalynx-select@0.0.1/dist/js/avalynx-select.js',
    'https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js',
    'https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js'
];

if ($currentStep === 'photo') {
    // Output HTML scripts
    echo html_scripts(
        includeJQuery: false,
        includeBootstrap: true,
        customScripts: $customScripts_photo,
        includeSwal: false,
        includeNotiflix: true
    );
} else {
    // Output HTML scripts
    echo html_scripts(
        includeJQuery: false,
        includeBootstrap: true,
        customScripts: $customScripts_normal,
        includeSwal: false,
        includeNotiflix: true
    );
}

?>

<script>
    const hu = '<?= $hu; ?>';
</script>
<script>
    document.querySelectorAll('.showQRCodeBtn').forEach(function(button) {
        button.addEventListener('click', function() {
            // Get the current URL
            var currentUrl = window.location.href;

            // Generate QR code using the 'toCanvas' method
            QRCode.toCanvas(document.getElementById("qrCode"), currentUrl, function(error) {
                if (error) console.error(error);
            });

            // Show the modal
            var qrModal = new bootstrap.Modal(document.getElementById('qrModal'));
            qrModal.show();
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const url = new URL(window.location.href);

        if (url.searchParams.has('through')) {
            url.searchParams.delete('through');

            // Prevent infinite reload loop
            if (!sessionStorage.getItem('throughParamRemoved')) {
                sessionStorage.setItem('throughParamRemoved', 'true');
                window.location.replace(url.toString()); // Reload with updated URL
            }
        }
    });
</script>
<?php if ($currentStep === 'persona'): ?>
    <script>
        let nationalities = [];
        let visaPax = <?= $visaPax['no_of_pax']; ?>;

        // countries list
        const countryIsoMapping = {
            "Afghan": {
                country: "Afghanistan",
                iso: "AF"
            },
            "Albanian": {
                country: "Albania",
                iso: "AL"
            },
            "Algerian": {
                country: "Algeria",
                iso: "DZ"
            },
            "American": {
                country: "United States",
                iso: "US"
            },
            "American Samoan": {
                country: "American Samoa",
                iso: "AS"
            },
            "Andorran": {
                country: "Andorra",
                iso: "AD"
            },
            "Angolan": {
                country: "Angola",
                iso: "AO"
            },
            "Antiguans": {
                country: "Antigua and Barbuda",
                iso: "AG"
            },
            "Argentinean": {
                country: "Argentina",
                iso: "AR"
            },
            "Armenian": {
                country: "Armenia",
                iso: "AM"
            },
            "Aruban": {
                country: "Aruba",
                iso: "AW"
            },
            "Australian": {
                country: "Australia",
                iso: "AU"
            },
            "Austrian": {
                country: "Austria",
                iso: "AT"
            },
            "Azerbaijani": {
                country: "Azerbaijan",
                iso: "AZ"
            },
            "Bahamian": {
                country: "Bahamas",
                iso: "BS"
            },
            "Bahraini": {
                country: "Bahrain",
                iso: "BH"
            },
            "Bangladeshi": {
                country: "Bangladesh",
                iso: "BD"
            },
            "Barbadian": {
                country: "Barbados",
                iso: "BB"
            },
            "Belarusian": {
                country: "Belarus",
                iso: "BY"
            },
            "Belgian": {
                country: "Belgium",
                iso: "BE"
            },
            "Belizean": {
                country: "Belize",
                iso: "BZ"
            },
            "Beninese": {
                country: "Benin",
                iso: "BJ"
            },
            "Bermudian": {
                country: "Bermuda",
                iso: "BM"
            },
            "Bhutanese": {
                country: "Bhutan",
                iso: "BT"
            },
            "Bolivian": {
                country: "Bolivia",
                iso: "BO"
            },
            "Bosnian": {
                country: "Bosnia and Herzegovina",
                iso: "BA"
            },
            "Botswanan": {
                country: "Botswana",
                iso: "BW"
            },
            "Brazilian": {
                country: "Brazil",
                iso: "BR"
            },
            "British": {
                country: "United Kingdom",
                iso: "GB"
            },
            "British Virgin Islander": {
                country: "British Virgin Islands",
                iso: "VG"
            },
            "Bruneian": {
                country: "Brunei",
                iso: "BN"
            },
            "Bulgarian": {
                country: "Bulgaria",
                iso: "BG"
            },
            "Burkinabe": {
                country: "Burkina Faso",
                iso: "BF"
            },
            "Burmese": {
                country: "Myanmar",
                iso: "MM"
            },
            "Burundian": {
                country: "Burundi",
                iso: "BI"
            },
            "Cambodian": {
                country: "Cambodia",
                iso: "KH"
            },
            "Cameroonian": {
                country: "Cameroon",
                iso: "CM"
            },
            "Canadian": {
                country: "Canada",
                iso: "CA"
            },
            "Cape Verdean": {
                country: "Cabo Verde",
                iso: "CV"
            },
            "Caymanian": {
                country: "Cayman Islands",
                iso: "KY"
            },
            "Central African": {
                country: "Central African Republic",
                iso: "CF"
            },
            "Chadian": {
                country: "Chad",
                iso: "TD"
            },
            "Chilean": {
                country: "Chile",
                iso: "CL"
            },
            "Chinese": {
                country: "China",
                iso: "CN"
            },
            "Colombian": {
                country: "Colombia",
                iso: "CO"
            },
            "Comoran": {
                country: "Comoros",
                iso: "KM"
            },
            "Congolese (DRC)": {
                country: "Democratic Republic of the Congo",
                iso: "CD"
            },
            "Congolese (Republic)": {
                country: "Republic of the Congo",
                iso: "CG"
            },
            "Cook Islander": {
                country: "Cook Islands",
                iso: "CK"
            },
            "Costa Rican": {
                country: "Costa Rica",
                iso: "CR"
            },
            "Croatian": {
                country: "Croatia",
                iso: "HR"
            },
            "Cuban": {
                country: "Cuba",
                iso: "CU"
            },
            "Curaçaoan": {
                country: "Curaçao",
                iso: "CW"
            },
            "Cypriot": {
                country: "Cyprus",
                iso: "CY"
            },
            "Czech": {
                country: "Czech Republic",
                iso: "CZ"
            },
            "Danish": {
                country: "Denmark",
                iso: "DK"
            },
            "Djiboutian": {
                country: "Djibouti",
                iso: "DJ"
            },
            "Dominican (Dominica)": {
                country: "Dominica",
                iso: "DM"
            },
            "Dominican (Republic)": {
                country: "Dominican Republic",
                iso: "DO"
            },
            "Dutch": {
                country: "Netherlands",
                iso: "NL"
            },
            "East Timorese": {
                country: "Timor-Leste",
                iso: "TL"
            },
            "Ecuadorean": {
                country: "Ecuador",
                iso: "EC"
            },
            "Egyptian": {
                country: "Egypt",
                iso: "EG"
            },
            "Emirati": {
                country: "United Arab Emirates",
                iso: "AE"
            },
            "English": {
                country: "United Kingdom",
                iso: "GB"
            },
            "Equatorial Guinean": {
                country: "Equatorial Guinea",
                iso: "GQ"
            },
            "Eritrean": {
                country: "Eritrea",
                iso: "ER"
            },
            "Estonian": {
                country: "Estonia",
                iso: "EE"
            },
            "Eswatini": {
                country: "Eswatini",
                iso: "SZ"
            },
            "Ethiopian": {
                country: "Ethiopia",
                iso: "ET"
            },
            "Falkland Islander": {
                country: "Falkland Islands",
                iso: "FK"
            },
            "Faroese": {
                country: "Faroe Islands",
                iso: "FO"
            },
            "Fijian": {
                country: "Fiji",
                iso: "FJ"
            },
            "Finnish": {
                country: "Finland",
                iso: "FI"
            },
            "French": {
                country: "France",
                iso: "FR"
            },
            "French Guianese": {
                country: "French Guiana",
                iso: "GF"
            },
            "French Polynesian": {
                country: "French Polynesia",
                iso: "PF"
            },
            "Gabonese": {
                country: "Gabon",
                iso: "GA"
            },
            "Gambian": {
                country: "Gambia",
                iso: "GM"
            },
            "Georgian": {
                country: "Georgia",
                iso: "GE"
            },
            "German": {
                country: "Germany",
                iso: "DE"
            },
            "Ghanaian": {
                country: "Ghana",
                iso: "GH"
            },
            "Gibraltarian": {
                country: "Gibraltar",
                iso: "GI"
            },
            "Greek": {
                country: "Greece",
                iso: "GR"
            },
            "Greenlandic": {
                country: "Greenland",
                iso: "GL"
            },
            "Grenadian": {
                country: "Grenada",
                iso: "GD"
            },
            "Guadeloupean": {
                country: "Guadeloupe",
                iso: "GP"
            },
            "Guamanian": {
                country: "Guam",
                iso: "GU"
            },
            "Guatemalan": {
                country: "Guatemala",
                iso: "GT"
            },
            "Guernsey": {
                country: "Guernsey",
                iso: "GG"
            },
            "Guinean": {
                country: "Guinea",
                iso: "GN"
            },
            "Bissau-Guinean": {
                country: "Guinea-Bissau",
                iso: "GW"
            },
            "Guyanese": {
                country: "Guyana",
                iso: "GY"
            },
            "Haitian": {
                country: "Haiti",
                iso: "HT"
            },
            "Hong Konger": {
                country: "Hong Kong",
                iso: "HK"
            },
            "Honduran": {
                country: "Honduras",
                iso: "HN"
            },
            "Hungarian": {
                country: "Hungary",
                iso: "HU"
            },
            "Icelander": {
                country: "Iceland",
                iso: "IS"
            },
            "Indian": {
                country: "India",
                iso: "IN"
            },
            "Indonesian": {
                country: "Indonesia",
                iso: "ID"
            },
            "Iranian": {
                country: "Iran",
                iso: "IR"
            },
            "Iraqi": {
                country: "Iraq",
                iso: "IQ"
            },
            "Irish": {
                country: "Ireland",
                iso: "IE"
            },
            "Israeli": {
                country: "Israel",
                iso: "IL"
            },
            "Italian": {
                country: "Italy",
                iso: "IT"
            },
            "Ivorian": {
                country: "Côte d'Ivoire",
                iso: "CI"
            },
            "Jamaican": {
                country: "Jamaica",
                iso: "JM"
            },
            "Japanese": {
                country: "Japan",
                iso: "JP"
            },
            "Jersey": {
                country: "Jersey",
                iso: "JE"
            },
            "Jordanian": {
                country: "Jordan",
                iso: "JO"
            },
            "Kazakhstani": {
                country: "Kazakhstan",
                iso: "KZ"
            },
            "Kenyan": {
                country: "Kenya",
                iso: "KE"
            },
            "I-Kiribati": {
                country: "Kiribati",
                iso: "KI"
            },
            "North Korean": {
                country: "North Korea",
                iso: "KP"
            },
            "South Korean": {
                country: "South Korea",
                iso: "KR"
            },
            "Kosovar": {
                country: "Kosovo",
                iso: "XK"
            },
            "Kuwaiti": {
                country: "Kuwait",
                iso: "KW"
            },
            "Kyrgyz": {
                country: "Kyrgyzstan",
                iso: "KG"
            },
            "Laotian": {
                country: "Laos",
                iso: "LA"
            },
            "Latvian": {
                country: "Latvia",
                iso: "LV"
            },
            "Lebanese": {
                country: "Lebanon",
                iso: "LB"
            },
            "Basotho": {
                country: "Lesotho",
                iso: "LS"
            },
            "Liberian": {
                country: "Liberia",
                iso: "LR"
            },
            "Libyan": {
                country: "Libya",
                iso: "LY"
            },
            "Liechtensteiner": {
                country: "Liechtenstein",
                iso: "LI"
            },
            "Lithuanian": {
                country: "Lithuania",
                iso: "LT"
            },
            "Luxembourger": {
                country: "Luxembourg",
                iso: "LU"
            },
            "Macaonese": {
                country: "Macao",
                iso: "MO"
            },
            "Macedonian": {
                country: "North Macedonia",
                iso: "MK"
            },
            "Malagasy": {
                country: "Madagascar",
                iso: "MG"
            },
            "Malawian": {
                country: "Malawi",
                iso: "MW"
            },
            "Malaysian": {
                country: "Malaysia",
                iso: "MY"
            },
            "Maldivian": {
                country: "Maldives",
                iso: "MV"
            },
            "Malian": {
                country: "Mali",
                iso: "ML"
            },
            "Maltese": {
                country: "Malta",
                iso: "MT"
            },
            "Manx": {
                country: "Isle of Man",
                iso: "IM"
            },
            "Marshallese": {
                country: "Marshall Islands",
                iso: "MH"
            },
            "Martiniquais": {
                country: "Martinique",
                iso: "MQ"
            },
            "Mauritanian": {
                country: "Mauritania",
                iso: "MR"
            },
            "Mauritian": {
                country: "Mauritius",
                iso: "MU"
            },
            "Mayottean": {
                country: "Mayotte",
                iso: "YT"
            },
            "Mexican": {
                country: "Mexico",
                iso: "MX"
            },
            "Micronesian": {
                country: "Micronesia",
                iso: "FM"
            },
            "Moldovan": {
                country: "Moldova",
                iso: "MD"
            },
            "Monegasque": {
                country: "Monaco",
                iso: "MC"
            },
            "Mongolian": {
                country: "Mongolia",
                iso: "MN"
            },
            "Montenegrin": {
                country: "Montenegro",
                iso: "ME"
            },
            "Montserratian": {
                country: "Montserrat",
                iso: "MS"
            },
            "Moroccan": {
                country: "Morocco",
                iso: "MA"
            },
            "Mozambican": {
                country: "Mozambique",
                iso: "MZ"
            },
            "Namibian": {
                country: "Namibia",
                iso: "NA"
            },
            "Nauruan": {
                country: "Nauru",
                iso: "NR"
            },
            "Nepalese": {
                country: "Nepal",
                iso: "NP"
            },
            "New Caledonian": {
                country: "New Caledonia",
                iso: "NC"
            },
            "New Zealander": {
                country: "New Zealand",
                iso: "NZ"
            },
            "Nicaraguan": {
                country: "Nicaragua",
                iso: "NI"
            },
            "Nigerien": {
                country: "Niger",
                iso: "NE"
            },
            "Nigerian": {
                country: "Nigeria",
                iso: "NG"
            },
            "Niuean": {
                country: "Niue",
                iso: "NU"
            },
            "Northern Irish": {
                country: "United Kingdom",
                iso: "GB"
            },
            "Northern Marianan": {
                country: "Northern Mariana Islands",
                iso: "MP"
            },
            "Norwegian": {
                country: "Norway",
                iso: "NO"
            },
            "Omani": {
                country: "Oman",
                iso: "OM"
            },
            "Pakistani": {
                country: "Pakistan",
                iso: "PK"
            },
            "Palauan": {
                country: "Palau",
                iso: "PW"
            },
            "Palestinian": {
                country: "Palestine",
                iso: "PS"
            },
            "Panamanian": {
                country: "Panama",
                iso: "PA"
            },
            "Papua New Guinean": {
                country: "Papua New Guinea",
                iso: "PG"
            },
            "Paraguayan": {
                country: "Paraguay",
                iso: "PY"
            },
            "Peruvian": {
                country: "Peru",
                iso: "PE"
            },
            "Filipino": {
                country: "Philippines",
                iso: "PH"
            },
            "Polish": {
                country: "Poland",
                iso: "PL"
            },
            "Portuguese": {
                country: "Portugal",
                iso: "PT"
            },
            "Puerto Rican": {
                country: "Puerto Rico",
                iso: "PR"
            },
            "Qatari": {
                country: "Qatar",
                iso: "QA"
            },
            "Réunionese": {
                country: "Réunion",
                iso: "RE"
            },
            "Romanian": {
                country: "Romania",
                iso: "RO"
            },
            "Russian": {
                country: "Russia",
                iso: "RU"
            },
            "Rwandan": {
                country: "Rwanda",
                iso: "RW"
            },
            "Sahrawi": {
                country: "Western Sahara",
                iso: "EH"
            },
            "Saint-Pierrais": {
                country: "Saint Pierre and Miquelon",
                iso: "PM"
            },
            "Kittitian or Nevisian": {
                country: "Saint Kitts and Nevis",
                iso: "KN"
            },
            "Saint Lucian": {
                country: "Saint Lucia",
                iso: "LC"
            },
            "Vincentian": {
                country: "Saint Vincent and the Grenadines",
                iso: "VC"
            },
            "Samoan": {
                country: "Samoa",
                iso: "WS"
            },
            "Sammarinese": {
                country: "San Marino",
                iso: "SM"
            },
            "São Toméan": {
                country: "Sao Tome and Principe",
                iso: "ST"
            },
            "Saudi": {
                country: "Saudi Arabia",
                iso: "SA"
            },
            "Scottish": {
                country: "United Kingdom",
                iso: "GB"
            },
            "Senegalese": {
                country: "Senegal",
                iso: "SN"
            },
            "Serbian": {
                country: "Serbia",
                iso: "RS"
            },
            "Seychellois": {
                country: "Seychelles",
                iso: "SC"
            },
            "Sierra Leonean": {
                country: "Sierra Leone",
                iso: "SL"
            },
            "Singaporean": {
                country: "Singapore",
                iso: "SG"
            },
            "Sint Maartener": {
                country: "Sint Maarten",
                iso: "SX"
            },
            "Slovak": {
                country: "Slovakia",
                iso: "SK"
            },
            "Slovenian": {
                country: "Slovenia",
                iso: "SI"
            },
            "Solomon Islander": {
                country: "Solomon Islands",
                iso: "SB"
            },
            "Somali": {
                country: "Somalia",
                iso: "SO"
            },
            "South African": {
                country: "South Africa",
                iso: "ZA"
            },
            "South Sudanese": {
                country: "South Sudan",
                iso: "SS"
            },
            "Spanish": {
                country: "Spain",
                iso: "ES"
            },
            "Sri Lankan": {
                country: "Sri Lanka",
                iso: "LK"
            },
            "Sudanese": {
                country: "Sudan",
                iso: "SD"
            },
            "Surinamese": {
                country: "Suriname",
                iso: "SR"
            },
            "Swazi": {
                country: "Eswatini",
                iso: "SZ"
            },
            "Swedish": {
                country: "Sweden",
                iso: "SE"
            },
            "Swiss": {
                country: "Switzerland",
                iso: "CH"
            },
            "Syrian": {
                country: "Syria",
                iso: "SY"
            },
            "Taiwanese": {
                country: "Taiwan",
                iso: "TW"
            },
            "Tajik": {
                country: "Tajikistan",
                iso: "TJ"
            },
            "Tanzanian": {
                country: "Tanzania",
                iso: "TZ"
            },
            "Thai": {
                country: "Thailand",
                iso: "TH"
            },
            "Togolese": {
                country: "Togo",
                iso: "TG"
            },
            "Tokelauan": {
                country: "Tokelau",
                iso: "TK"
            },
            "Tongan": {
                country: "Tonga",
                iso: "TO"
            },
            "Trinidadian or Tobagonian": {
                country: "Trinidad and Tobago",
                iso: "TT"
            },
            "Tunisian": {
                country: "Tunisia",
                iso: "TN"
            },
            "Turkish": {
                country: "Turkey",
                iso: "TR"
            },
            "Turkmen": {
                country: "Turkmenistan",
                iso: "TM"
            },
            "Tuvaluan": {
                country: "Tuvalu",
                iso: "TV"
            },
            "Ugandan": {
                country: "Uganda",
                iso: "UG"
            },
            "Ukrainian": {
                country: "Ukraine",
                iso: "UA"
            },
            "Uruguayan": {
                country: "Uruguay",
                iso: "UY"
            },
            "US Virgin Islander": {
                country: "U.S. Virgin Islands",
                iso: "VI"
            },
            "Uzbek": {
                country: "Uzbekistan",
                iso: "UZ"
            },
            "Ni-Vanuatu": {
                country: "Vanuatu",
                iso: "VU"
            },
            "Vatican": {
                country: "Vatican City",
                iso: "VA"
            },
            "Venezuelan": {
                country: "Venezuela",
                iso: "VE"
            },
            "Vietnamese": {
                country: "Vietnam",
                iso: "VN"
            },
            "Wallisian or Futunan": {
                country: "Wallis and Futuna",
                iso: "WF"
            },
            "Welsh": {
                country: "United Kingdom",
                iso: "GB"
            },
            "Yemeni": {
                country: "Yemen",
                iso: "YE"
            },
            "Zambian": {
                country: "Zambia",
                iso: "ZM"
            },
            "Zimbabwean": {
                country: "Zimbabwe",
                iso: "ZW"
            },
            "Salvadoran": {
                country: "El Salvador",
                iso: "SV"
            }
        };

        // Ensure orderId is properly assigned
        const orderId = '<?= $order_id; ?>';

        // Load nationalities first, then fetch travelers
        document.addEventListener('DOMContentLoaded', () => {
            fetch('assets/nationalities.json')
                .then(response => response.json()) // Correctly parse JSON
                .then(nationalities => {
                    // Store for later use (example)
                    window.nationalitiesData = nationalities;

                    // Fetch travelers only after nationalities are loaded
                    fetchTravelers();
                })
                .catch(error => {
                    console.error('Error loading nationalities:', error);
                });
        });

        function fetchTravelers() {
            fetch(`api/v1/getTraveler?order_id=${orderId}`, {
                    method: 'GET',
                    headers: {
                        'HU': '<?= $hu; ?>',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const travelers = data?.data;
                    if (Array.isArray(travelers) && travelers.length > 0) {
                        travelers.forEach((traveler, index) => {
                            addTravelerForm(index + 1, traveler); // Render each traveler form
                        });
                    } else {
                        Notiflix.Notify.failure('No travelers found for the given Order ID.');
                        const noOfPax = <?= $visaPax['no_of_pax']; ?>; // Get the number of travelers
                        for (let i = 1; i <= noOfPax; i++) {
                            addTravelerForm(i, null); // Render a blank form for each traveler
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching travelers:', error);
                });
        }

        function addTravelerForm(travelerNumber, travelerData = {}) {
            const container = document.getElementById('travelerFieldsContainer');
            const travelerDiv = document.createElement('div');
            travelerDiv.classList.add('traveler', 'mb-3');
            travelerDiv.dataset.index = travelerNumber;

            const nationalityOptions = window.nationalitiesData.map(n => `<option value="${n}">${n}</option>`).join('');

            const passportCountryOptions = Object.values(countryIsoMapping)
                .map(c => `<option value="${c.iso}" data-iso="${c.iso}">${c.country}</option>`)
                .join('');

            const name = travelerData?.name || '';
            const passportNumber = travelerData?.passport_number || '';
            const dob = travelerData?.date_of_birth ? new Date(travelerData.date_of_birth).toISOString().split('T')[0] : ''; // Format to YYYY-MM-DD
            const nationality = travelerData?.nationality || '';
            const passportCountry = travelerData?.passport_issuing_country || '';
            const passportCountryCode = travelerData?.passport_issuing_country || '';

            travelerDiv.innerHTML = `
                <h5 class="fw-bold" id="travelerHeading_${travelerNumber}">Traveler ${travelerNumber}</h5>
                <div class="mb-3">
                    <label class="form-label">Name <span class="text-muted">(must match your passport)</span></label>
                    <input type="text" class="form-control" name="name_${travelerNumber}" value="${name}" required 
                        oninput="this.value = this.value.replace(/\\b\\w/g, char => char.toUpperCase()); validateNameInput(this.value, ${travelerNumber}); updateTravelerHeading(${travelerNumber}, this.value)">
                    <div class="invalid-feedback">Name is required.</div>
                </div>
                <div class="mb-3">
                    <label>Passport Number</label>
                    <input type="text" class="form-control passport-input" name="passport_${travelerNumber}" 
                        value="${passportNumber}" pattern="^(?!^0+$)[A-Z0-9]{5,15}$" required>
                    <div class="invalid-feedback">Passport number is required or invalid.</div>
                    <div class="valid-feedback">Looks good!</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" name="dob_${travelerNumber}" value="${dob}" max="${new Date().toISOString().split('T')[0]}" required>
                    <div class="invalid-feedback">Date of birth is required.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nationality</label>
                    <select class="form-select" name="nationality_${travelerNumber}" id="nationality_${travelerNumber}" required onchange="matchNationalityWithPassport(${travelerNumber})">
                        <option value="" selected disabled>Please choose</option>
                        ${nationalityOptions}
                    </select>
                    <div class="invalid-feedback">Please select a nationality.</div>
                </div>
                <div class="mb-2" id="passportCountrydiv_${travelerNumber}">
                    <label class="form-label">Passport Issuing Country</label>
                    <select class="form-select" name="passport_country_${travelerNumber}" 
                            id="passportCountry_${travelerNumber}" required 
                            onchange="setHiddenField(this, ${travelerNumber})">
                        <option value="">Choose a country</option>
                        ${passportCountryOptions}
                    </select>
                    <input type="hidden" id="passportCountryCode_${travelerNumber}" name="passport_country_code_${travelerNumber}" value="${passportCountryCode}">
                    <div class="invalid-feedback">Passport issuing country is required.</div>
                </div> 
                <input type="hidden" name="order_id" value="${travelerData?.order_id || '<?= $order_id; ?>' }">
                <input type="hidden" name="action" value="${travelerData?.order_id ? 'update' : 'create'}">            
        `;

            container.appendChild(travelerDiv);
            travelerDiv.style.cssText = "border-bottom: 1px dashed #ccc; margin: 10px 0; padding-bottom: 10px;";

            if (travelerData && Object.keys(travelerData).length > 0) {
                const nationalitySelect = document.querySelector(`#nationality_${travelerNumber}`);
                const passportCountrySelect = document.querySelector(`#passportCountry_${travelerNumber}`);

                if (nationalitySelect) nationalitySelect.value = nationality;
                if (passportCountrySelect) passportCountrySelect.value = passportCountry;
            }
        }


        function updateTravelerHeading(travelerNumber, name) {
            const heading = document.getElementById(`travelerHeading_${travelerNumber}`);
            heading.textContent = name ? `Traveler ${travelerNumber}: ${name}` : `Traveler ${travelerNumber}`;
        }

        function setHiddenField(selectElement, travelerNumber) {
            const hiddenInput = document.getElementById(`passportCountryCode_${travelerNumber}`);
            hiddenInput.value = selectElement.value;
        }

        document.addEventListener('input', function(event) {
            if (event.target.classList.contains('passport-input')) {
                const input = event.target;

                // Clear any previous custom validity message
                input.setCustomValidity('');

                // Trim input value
                const value = input.value.trim();

                // Clear previous validation classes
                input.classList.remove('is-valid', 'is-invalid');

                // Apply validation logic
                if (value === '') {
                    input.setCustomValidity('Passport number is required.');
                    input.classList.add('is-invalid');
                } else if (!input.checkValidity()) {
                    input.setCustomValidity('Invalid passport number format.');
                    input.classList.add('is-invalid');
                } else {
                    input.classList.add('is-valid');
                }

                // Trigger real-time feedback
                input.reportValidity();
            }
        });



        // Function to auto-match nationality with passport country
        // Auto-match Nationality with Passport Country
        function matchNationalityWithPassport(travelerNumber) {
            const nationalitySelect = document.getElementById(`nationality_${travelerNumber}`);
            const selectedNationality = nationalitySelect.value;
            const mapping = countryIsoMapping[selectedNationality];

            if (mapping) {
                const passportCountrySelect = document.getElementById(`passportCountry_${travelerNumber}`);
                const options = passportCountrySelect.options;

                for (let i = 0; i < options.length; i++) {
                    if (options[i].value === mapping.iso) {
                        passportCountrySelect.selectedIndex = i;
                        setHiddenField(passportCountrySelect, travelerNumber);
                        break;
                    }
                }
            }
        }

        // Form Submission with Validation
        document.getElementById('travelerForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            const form = this;
            if (!form.checkValidity()) {
                event.stopPropagation();
                form.classList.add('was-validated');
                return; // Stop submission if validation fails
            }

            // Convert form data to JSON
            const formData = new FormData(form);
            const jsonData = {};
            formData.forEach((value, key) => {
                jsonData[key] = value;
            });

            // Determine action: create or update
            const action = formData.get('action'); // 'create' or 'update'
            const url = action === 'update' ? `api/v1/travelers?do=update` : 'api/v1/travelers';
            const method = action === 'update' ? 'POST' : 'POST';

            // Set headers
            const headers = new Headers();
            headers.append('HU', '<?= $hu; ?>');
            headers.append('Content-Type', 'application/json'); // Important for JSON

            // Send data as JSON
            fetch(url, {
                    method: method,
                    headers: headers,
                    body: JSON.stringify(jsonData), // Send JSON data
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Notiflix.Notify.success(data.message || 'Traveler data submitted successfully!');
                        form.reset();
                        form.classList.remove("was-validated");
                        location.href = `application/<?= $order_id; ?>/photo`; // Redirect to photo upload
                    } else if (data.status === 'error') {
                        Notiflix.Notify.failure(data.message || 'Error submitting traveler data.');
                    } else {
                        Notiflix.Notify.failure('Unexpected response from server.');
                    }
                })
                .catch(error => {
                    console.error('Network error:', error);
                    Notiflix.Notify.failure('Network error while submitting traveler data.');
                });
        });

        function validateNameInput(value, travelerNumber) {
            const input = document.querySelector(`input[name="name_${travelerNumber}"]`);
            if (!input) return;

            // Clear previous errors
            const existingError = input.parentNode.querySelector('.error-message');
            if (existingError) existingError.remove();

            // Regex: Allows letters, spaces, hyphens, and apostrophes
            // Prevents consecutive hyphens/apostrophes (e.g., "Jr--Doe" or "O''Reilly")
            const isValid = /^[A-Za-z]+(?:[ '-][A-Za-z]+)*$/.test(value.trim());

            // Show error if invalid
            if (!isValid) {
                input.setCustomValidity('Name can only contain letters, spaces, hyphens (-), and apostrophes (\'). No repeated symbols.');
                input.classList.add('is-invalid');

                // Create and display error message
                const errorMessage = document.createElement('div');
                errorMessage.className = 'error-message text-danger';
                errorMessage.textContent = 'Invalid name. Use only letters, spaces, hyphens (-), or apostrophes (\').';
                input.parentNode.appendChild(errorMessage);
            } else {
                input.setCustomValidity('');
                input.classList.remove('is-invalid');
            }
        }
    </script>
<?php endif; ?>

<!-- Upload Photo -->
<?php if ($currentStep === 'photo') { ?>
    <script>
        // Trigger file input when clicking on the upload container
        function triggerFileInput(index) {
            document.getElementById(`photoInput_${index}`).click();
        }

        // Show upload overlay
        function showUploadOverlay() {
            document.getElementById('uploadOverlay').style.display = 'flex';
        }

        // Hide upload overlay
        function hideUploadOverlay() {
            document.getElementById('uploadOverlay').style.display = 'none';
        }


        // Handle file upload
        async function handleFileUpload(file, travelerId, index) {
            if (!file || !['image/jpeg', 'image/jpg', 'image/png', 'image/webp'].includes(file.type) || file.size > 5 * 1024 * 1024) {
                Notiflix.Notify.failure('Invalid file. Only JPG, PNG, WebP under 5MB allowed.');
                return;
            }

            const inputEl = document.getElementById(`photoInput_${index}`);
            const previewEl = document.getElementById(`photoUploadPreview_${index}`);
            const placeholderEl = document.getElementById(`photoPlaceholder_${index}`);
            const deleteBtnEl = document.querySelector(`#photoUploadPreview_${index} + .position-absolute .btn-danger`);

            const formData = new FormData();
            formData.append('file', file);
            showUploadOverlay(); // Show the overlay

            try {
                const response = await fetch('api/v1/uploadPhoto', {
                    method: 'POST',
                    headers: {
                        'HU': '<?= $hu; ?>',
                        'X-Person-Name': inputEl.dataset.personName,
                        'X-Order-ID': '<?= $order_id; ?>',
                        'X-Traveler-ID': travelerId
                    },
                    body: formData
                });

                const data = await response.json();
                document.getElementById('uploadOverlay').style.display = 'none';

                if (data.success) {
                    previewEl.src = data.photo_url.replace('../../', '');
                    previewEl.classList.remove('d-none');
                    if (placeholderEl) placeholderEl.style.display = 'none';
                    if (deleteBtnEl) deleteBtnEl.style.display = 'block';
                    Notiflix.Notify.success(data.message || 'Upload successful.');
                    setInterval(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    Notiflix.Notify.failure(data.error || 'Upload failed. Please try again.');
                }
            } catch (error) {
                document.getElementById('uploadOverlay').style.display = 'none';
                Notiflix.Notify.failure('Error uploading photo.');
            } finally {
                hideUploadOverlay(); // Ensure overlay is hidden after the request completes
            }
        }

        // Handle drag-and-drop
        function handleDrop(event, index) {
            event.preventDefault();
            const file = event.dataTransfer.files[0];
            if (file) {
                const travelerId = document.getElementById(`photoInput_${index}`).dataset.travelerId;
                handleFileUpload(file, travelerId, index);
            }
        }

        // Delete photo
        async function deletePhoto(travelerId, index) {
            if (!confirm('Are you sure you want to delete this photo?')) return;

            const inputEl = document.getElementById(`photoInput_${index}`);
            const previewEl = document.getElementById(`photoUploadPreview_${index}`);
            const placeholderEl = document.getElementById(`photoPlaceholder_${index}`);
            const deleteBtnEl = document.querySelector(`#photoUploadPreview_${index} + .position-absolute .btn-danger`);

            try {
                const response = await fetch('api/v1/deletePic', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'HU': '<?= $hu; ?>',
                        'X-Order-ID': '<?= $order_id; ?>',
                        'X-Person-Name': inputEl.dataset.personName
                    },
                    body: JSON.stringify({
                        traveler_id: travelerId
                    })
                });

                const data = await response.json();
                if (data.success) {
                    if (previewEl) previewEl.classList.add('d-none');
                    if (placeholderEl) placeholderEl.style.display = 'flex';
                    if (deleteBtnEl) deleteBtnEl.style.display = 'none';
                    Notiflix.Notify.success('Photo deleted successfully.');
                    setInterval(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    Notiflix.Notify.failure(data.error || 'Failed to delete photo. Please try again.');
                }
            } catch (error) {
                Notiflix.Notify.failure('Error deleting photo.');
            }
        }

        // Add event listeners for file inputs
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function() {
                const travelerId = this.dataset.travelerId;
                const index = this.id.split('_')[1];
                handleFileUpload(this.files[0], travelerId, index);
            });
        });

        // Check if photo is uploaded the only move to navigation
        let photoPreviews = document.querySelectorAll('[id^="photoPreview_"]');
        let saveNextBtn = document.querySelector('#saveNextBtn');

        // Log all found photoPreviews
        photoPreviews.forEach((element) => {
            console.log(`Found ${element.id}`);
        });

        // Add event listener to saveNextBtn
        if (saveNextBtn) {
            saveNextBtn.addEventListener('click', (event) => {
                // Check if no photoPreviews are found
                if (photoPreviews.length === 0) {
                    console.log('No photoPreviews found, navigation stopped.');
                    event.preventDefault(); // Stop the default navigation behavior
                    // alert('No photoPreviews found. Navigation stopped.'); // Optional: Notify the user
                    // Notify the use to upload even 1 photo
                    Notiflix.Notify.info('Please upload at least one photograph of the traveller.');
                } else {
                    console.log('Photo Previews found, navigation allowed.');
                    // Do nothing, allow the default behavior (follow the href route)
                }
            });
        }
    </script>


<?php } ?>

<!-- Upload Passport -->
<?php if ($currentStep === 'passport') { ?>
    <script>
        // Trigger file input when clicking on the upload container
        function triggerFileInput(index) {
            document.getElementById(`passportInput_${index}`).click();
        }

        // Show upload overlay
        function showUploadOverlay() {
            document.getElementById('uploadOverlay').style.display = 'flex';
        }

        // Hide upload overlay
        function hideUploadOverlay() {
            document.getElementById('uploadOverlay').style.display = 'none';
        }


        // Handle file upload
        async function handleFileUpload(file, travelerId, index) {
            if (!file || !['image/jpeg', 'image/jpg', 'image/png', 'image/webp'].includes(file.type) || file.size > 5 * 1024 * 1024) {
                Notiflix.Notify.failure('Invalid file. Only JPG, PNG, WebP under 5MB allowed.');
                return;
            }

            const inputEl = document.getElementById(`passportInput_${index}`);
            const previewEl = document.getElementById(`passportUploadPreview_${index}`);
            const placeholderEl = document.getElementById(`passportPlaceholder_${index}`);
            const deleteBtnEl = document.querySelector(`#passportUploadPreview_${index} + .position-absolute .btn-danger`);

            const formData = new FormData();
            formData.append('file', file);
            showUploadOverlay(); // Show the overlay

            try {
                const response = await fetch('api/v1/uploadPassport', {
                    method: 'POST',
                    headers: {
                        'HU': '<?= $hu; ?>',
                        'X-Person-Name': inputEl.dataset.personName,
                        'X-Order-ID': '<?= $order_id; ?>',
                        'X-Traveler-ID': travelerId
                    },
                    body: formData
                });

                console.log(response); // Log the response object
                const data = await response.json();
                console.log(data); // Log the parsed response data

                if (!response.ok) { // Check if the response was successful
                    throw new Error('Network response was not ok');
                }

                console.log(data.success);
                console.log(data.error);

                document.getElementById('uploadOverlay').style.display = 'none';

                if (response.ok || data.success) {
                    // previewEl.src = data.passport_url.replace('../../', '');
                    previewEl.classList.remove('d-none');
                    if (placeholderEl) placeholderEl.style.display = 'none';
                    if (deleteBtnEl) deleteBtnEl.style.display = 'block';
                    Notiflix.Notify.success(data.message || 'Upload successful.');
                    setInterval(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    Notiflix.Notify.failure(data.error || 'Upload failed. Please try again.');
                }
            } catch (error) {
                console.error('Error during fetch:', error); // Log the error
                document.getElementById('uploadOverlay').style.display = 'none';
                Notiflix.Notify.failure('Error uploading passport.');
            } finally {
                hideUploadOverlay(); // Ensure overlay is hidden after the request completes
            }
        }

        // Handle drag-and-drop
        function handleDrop(event, index) {
            event.preventDefault();
            const file = event.dataTransfer.files[0];
            if (file) {
                const travelerId = document.getElementById(`passportInput_${index}`).dataset.travelerId;
                handleFileUpload(file, travelerId, index);
            }
        }

        // Delete passport
        async function deletepassport(travelerId, index) {
            if (!confirm('Are you sure you want to delete this passport?')) return;

            const inputEl = document.getElementById(`passportInput_${index}`);
            const previewEl = document.getElementById(`passportUploadPreview_${index}`);
            const placeholderEl = document.getElementById(`passportPlaceholder_${index}`);
            const deleteBtnEl = document.querySelector(`#passportUploadPreview_${index} + .position-absolute .btn-danger`);

            try {
                const response = await fetch('api/v1/deletePassport', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'HU': '<?= $hu; ?>',
                        'X-Order-ID': '<?= $order_id; ?>',
                        'X-Person-Name': inputEl.dataset.personName
                    },
                    body: JSON.stringify({
                        traveler_id: travelerId
                    })
                });

                const data = await response.json();
                if (data.success) {
                    if (previewEl) previewEl.classList.add('d-none');
                    if (placeholderEl) placeholderEl.style.display = 'flex';
                    if (deleteBtnEl) deleteBtnEl.style.display = 'none';
                    Notiflix.Notify.success('passport deleted successfully.');
                    setInterval(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    Notiflix.Notify.failure(data.error || 'Failed to delete passport. Please try again.');
                }
            } catch (error) {
                Notiflix.Notify.failure('Error deleting passport.');
            }
        }

        // Add event listeners for file inputs
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function() {
                const travelerId = this.dataset.travelerId;
                const index = this.id.split('_')[1];
                handleFileUpload(this.files[0], travelerId, index);
            });
        });

        // Check if passport is uploaded the only move to navigation
        let passportPreviews = document.querySelectorAll('[id^="passportPreview_"]');
        let saveNextBtn1 = document.querySelector('#saveNextBtn');

        // Log all found passportPreviews
        passportPreviews.forEach((element) => {
            console.log(`Found ${element.id}`);
        });

        // Add event listener to saveNextBtn
        if (saveNextBtn1) {
            saveNextBtn1.addEventListener('click', (event) => {
                // Check if no passportPreviews are found
                if (passportPreviews.length === 0) {
                    console.log('No passportPreviews found, navigation stopped.');
                    event.preventDefault(); // Stop the default navigation behavior
                    // alert('No passportPreviews found. Navigation stopped.'); // Optional: Notify the user
                    // Notify the use to upload even 1 passport
                    Notiflix.Notify.info('Please upload at least one photo of the passport of the traveller.');
                } else {
                    console.log('passportPreviews found, navigation allowed.');
                    // Do nothing, allow the default behavior (follow the href route)
                }
            });
        }
    </script>
<?php } ?>


<?php if ($currentStep === 'other documents') { ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".doc_uploader").forEach((doc_uploader) => {
                const fileInput = doc_uploader.querySelector(".file-input");
                const uploadClick = doc_uploader.querySelector(".upload-click");
                const fileListContainer = document.getElementById(`file-list-${doc_uploader.dataset.traveler_id}-${doc_uploader.dataset.doc}`);
                const uploadedDocuments = document.getElementById("uploadedDocuments");

                const doc_uploaderMeta = {
                    traveler_id: doc_uploader.dataset.traveler_id || "",
                    document_id: doc_uploader.dataset.doc || "",
                    person_name: doc_uploader.dataset.person_name || "",
                    order_id: "<?= $order_id; ?>", // Ensure PHP correctly outputs the value
                    hu: "<?= $hu; ?>"
                };

                // Click event to trigger file selection
                uploadClick.addEventListener("click", () => fileInput.click());

                // Drag & Drop Events
                doc_uploader.addEventListener("dragover", (e) => {
                    e.preventDefault();
                    doc_uploader.classList.add("dragover");
                });

                doc_uploader.addEventListener("dragleave", () => {
                    doc_uploader.classList.remove("dragover");
                });

                doc_uploader.addEventListener("drop", (e) => {
                    e.preventDefault();
                    doc_uploader.classList.remove("dragover");

                    const files = e.dataTransfer.files;
                    handleFileUpload(files, doc_uploader, fileListContainer, uploadedDocuments, doc_uploaderMeta);
                });

                // File selection event
                fileInput.addEventListener("change", (event) => {
                    handleFileUpload(event.target.files, doc_uploader, fileListContainer, uploadedDocuments, doc_uploaderMeta);
                });
            });

            async function handleFileUpload(files, doc_uploader, fileListContainer, uploadedDocuments, doc_uploaderMeta) {
                if (!files.length) return;

                // Show upload overlay
                function showUploadOverlay() {
                    document.getElementById('uploadOverlay').style.display = 'flex';
                }

                // Hide upload overlay
                function hideUploadOverlay() {
                    document.getElementById('uploadOverlay').style.display = 'none';
                }

                for (const file of files) {
                    const formData = new FormData();
                    formData.append("file", file);
                    formData.append("traveler_id", doc_uploaderMeta.traveler_id);
                    formData.append("document_id", doc_uploaderMeta.document_id);
                    formData.append("person_name", doc_uploaderMeta.person_name);
                    showUploadOverlay(); // Show the overlay
                    try {
                        const response = await fetch("api/v1/uploadDocument", {
                            method: "POST",
                            headers: {
                                "X-Traveler-ID": doc_uploaderMeta.traveler_id,
                                "X-Document-ID": doc_uploaderMeta.document_id,
                                "X-Person-Name": doc_uploaderMeta.person_name,
                                "X-Order-ID": doc_uploaderMeta.order_id,
                                "HU": doc_uploaderMeta.hu
                            },
                            body: formData,
                        });

                        const result = await response.json();

                        if (result.status === "success") {
                            doc_uploader.style.display = "none"; // Hide doc_uploader on success
                            // displayUploadedDocument(result.document_name, uploadedDocuments);
                            location.reload();
                        } else {
                            // alert(result.message || "File upload failed.");
                            Notiflix.Notify.failure(result.message || "File upload failed.");

                        }
                    } catch (error) {
                        console.error("Error uploading file:", error);
                        // alert("Error  file. Please try again.");
                        Notiflix.Notify.failure('Error uploading file. Please try again.');

                    } finally {
                        hideUploadOverlay(); // Ensure overlay is hidden after the request completes
                    }
                }
            }

            // Delete document
            async function handleDelete(fileName, travelerId, travelerName) {
                if (!confirm("Are you sure you want to delete this file?")) return;

                try {
                    const response = await fetch("api/v1/deleteDocument.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "HU": "<?= $hu; ?>",
                            "X-Order-Id": "<?= $order_id; ?>",
                            "X-Person-Name": travelerName
                        },
                        body: JSON.stringify({
                            file_name: fileName,
                            traveler_id: travelerId
                        }),
                    });

                    const result = await response.json();

                    if (result.status === "success") {
                        Notiflix.Notify.success(result.message || "File deleted successfully.");
                        location.reload();
                    } else {
                        Notiflix.Notify.failure(result.message || "Failed to delete file.");
                    }
                } catch (error) {
                    Notiflix.Notify.failure('Error deleting file. Please try again.');
                }
            }

            // Add event listeners for delete buttons
            document.querySelectorAll('[data-action="delete"]').forEach(button => {
                button.addEventListener('click', function() {
                    const fileName = this.dataset.file;
                    const travelerId = this.dataset.travelerId;
                    const travelerName = this.dataset.travelerName;
                    handleDelete(fileName, travelerId, travelerName);
                });
            });

        });

        const saveNextBtnDocs = document.querySelector('#saveNextBtnDocs');

        if (saveNextBtnDocs) {
            saveNextBtnDocs.addEventListener('click', (event) => {
                // Query the DOM for the current list of uploadedDivs
                const uploadedDivs = document.querySelectorAll('.uploadedDiv');

                // Debug: log the number of uploadedDivs found
                console.log(`Found ${uploadedDivs.length} uploadedDiv(s).`);

                // If no uploadedDivs are found, stop navigation and notify the user
                if (uploadedDivs.length === 0) {
                    console.log('No uploadedDivs found, navigation stopped.');
                    event.preventDefault(); // Prevent default behavior
                    Notiflix.Notify.info('Please upload at least one required document of the traveller.');
                } else {
                    console.log('Photo previews found, navigation allowed.');
                    // Navigation is allowed (default behavior will occur)
                }
            });
        }
    </script>




<?php } ?>

<!-- DETAILS -->
<?php if ($currentStep === 'details'): ?>
    <!-- DETAILS -->
    <script>
        const errorMsg = document.querySelector('#errorMsg');
        document.addEventListener("DOMContentLoaded", function() {
            initializeFormValidation();
            initializeDropdowns();
            setupMaritalStatusListener();
        });

        // 🛠 Initialize form validation
        function initializeFormValidation() {
            document.querySelectorAll('.needs-validation').forEach(form => {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                    form.classList.add('was-validated');

                    validateInputs(form);
                    if (form.checkValidity()) {
                        submitForm(form);
                    } else {

                        // Notififlix.Notify.failure('Please fill up the required fields.');
                        Notiflix.Notify.failure('Please fill up all the required fields.');
                    }
                });
            });
        }

        // ✅ Validate form inputs and properly toggle Bootstrap feedback
        function validateInputs(form) {
            form.querySelectorAll('input, select, textarea').forEach(input => {
                input.addEventListener('input', () => validateSingleInput(input));
                validateSingleInput(input); // Run validation on load
            });
        }

        // ✅ Validate a single input field
        function validateSingleInput(input) {
            const validFeedback = input.parentNode.querySelector('.valid-feedback');
            const invalidFeedback = input.parentNode.querySelector('.invalid-feedback');

            if (input.checkValidity()) {
                input.classList.add('is-valid');
                input.classList.remove('is-invalid');

                if (validFeedback) validFeedback.style.display = "block"; // Show valid feedback
                if (invalidFeedback) invalidFeedback.style.display = "none"; // Hide invalid feedback
            } else {
                input.classList.add('is-invalid');
                input.classList.remove('is-valid');

                if (invalidFeedback) invalidFeedback.style.display = "block"; // Show invalid feedback
                if (validFeedback) validFeedback.style.display = "none"; // Hide valid feedback
            }
        }



        // 📄 Submit form data via AJAX
        function submitForm(form) {
            const data = Object.fromEntries(new FormData(form).entries());

            fetch('api/v1/details', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'HU': '<?= $hu; ?>',
                        'X-Order-Id': '<?= $order_id; ?>'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    switch (result.message) {
                        case 'next_traveler':
                            // Remove any 'tid=' prefix from result.tid before using it
                            const cleanTid = result.tid.replace(/^tid=/, '');
                            window.location.href = `application/<?= $order_id; ?>/details?tid=${cleanTid}`;
                            break;
                        case 'make_payment':
                            window.location.href = `application/<?= $order_id; ?>/checkout`;
                            break;
                        default:
                            showAlert(form, result.message, result.success);
                    }
                })
                .catch(() => showAlert(form, 'An error occurred while submitting the form.', false));
        }

        // 💾 Save draft with step parameter
        function saveDraft(step) {
            const form = document.querySelector('#evisaForm');
            if (!form) return;

            const data = Object.fromEntries(new FormData(form).entries());
            data.isDraft = true;
            data.step = step;

            fetch('api/v1/saveData', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'HU': '<?= $hu; ?>',
                        'X-Order-Id': '<?= $order_id; ?>'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => Notiflix.Notify.success(`Step ${step}: ${result.message}`))
                .catch(error => Notififlix.Notify.failure('Error:', error));
        }

        // 🔔 Show alert messages
        function showAlert(form, message, isSuccess) {
            const alertBox = document.createElement('div');
            alertBox.className = `alert mt-3 ${isSuccess ? 'alert-success' : 'alert-danger'}`;
            alertBox.textContent = message;
            form.appendChild(alertBox);
        }

        // 🌍 Load dropdown data from assets/*.json
        function initializeDropdowns() {
            const dropdownMappings = {
                'assets/countries.json': {
                    id: 'countrySelect',
                    key: 'countries',
                    selectedValue: <?php echo json_encode($applicantData['country_of_birth'] ?? ''); ?>
                },
                'assets/races.json': {
                    id: 'raceSelect',
                    key: 'races',
                    selectedValue: <?php echo json_encode($applicantData['race'] ?? ''); ?>
                },
                'assets/nationalities_new.json': {
                    id: 'nationalitySelect',
                    key: 'nationalities',
                    selectedValue: <?php echo json_encode($applicant['nationality'] ?? ''); ?>
                },
                'assets/religions.json': {
                    id: 'religionSelect',
                    key: 'religions',
                    selectedValue: <?php echo json_encode($applicantData['religion'] ?? ''); ?>
                }
            };

            Object.entries(dropdownMappings).forEach(([url, {
                id,
                key,
                selectedValue
            }]) => {
                loadDropdownData(url, id, key, selectedValue);
            });
        }

        // 🔄 Fetch and populate dropdowns
        function loadDropdownData(url, selectId, key, selectedValue) {
            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error(`Failed to load ${url}`);
                    return response.json();
                })
                .then(data => {
                    // Special handling for 'races' dropdown
                    if (key === 'races') {
                        populateRaceDropdown(selectId, data[key] || [], selectedValue);
                    } else {
                        populateDropdown(selectId, data[key] || [], selectedValue);
                    }
                })
                .catch(error => console.error(`Error loading ${key}:`, error));
        }


        // 🏛 Populate select dropdown
        function populateDropdown(selectId, items, selectedValue = "") {
            const select = document.getElementById(selectId);
            if (!select) return;

            // Clear existing options
            select.innerHTML = '<option value="" selected disabled>Please Choose</option>';

            let hasSelected = false;

            items.forEach(item => {
                const option = document.createElement("option");
                option.value = item.code || item.value || item;
                option.textContent = item.name || item;

                // Preselect the option if it matches the selectedValue
                if (selectedValue && option.value === selectedValue) {
                    option.selected = true;
                    hasSelected = true;
                }

                select.appendChild(option);
            });

            // Ensure the first option remains selected if no valid match was found
            if (!hasSelected) {
                select.selectedIndex = 0;
            }
        }

        // 🏛 Populate race dropdown (for raceSelect)
        function populateRaceDropdown(selectId, items, selectedValue = "") {
            const select = document.getElementById(selectId);
            if (!select) return;

            // Clear existing options
            select.innerHTML = '<option value="" selected disabled>Please Choose</option>';

            let hasSelected = false;

            items.forEach(item => {
                const option = document.createElement("option");

                // Directly use the item (string) for value and text
                option.value = item;
                option.textContent = item;

                // Preselect the option if it matches the selectedValue
                if (selectedValue && option.value === selectedValue) {
                    option.selected = true;
                    hasSelected = true;
                }

                select.appendChild(option);
            });

            // Ensure the first option remains selected if no valid match was found
            if (!hasSelected) {
                select.selectedIndex = 0;
            }
        }

        // 🏁 Initialize dropdowns on page load
        document.addEventListener("DOMContentLoaded", initializeDropdowns);


        // 💑 Show/hide spouse nationality field based on marital status
        function setupMaritalStatusListener() {
            const maritalStatus = document.getElementById('maritalStatus');
            const spouseField = document.getElementById('spouseNationalityField');

            if (!maritalStatus || !spouseField) return;

            maritalStatus.addEventListener('change', function() {
                spouseField.style.display = (this.value === 'Married') ? 'block' : 'none';
            });
        }

        // Load countries for address info
        document.addEventListener("DOMContentLoaded", function() {
            // Load countries from JSON
            fetch("assets/countries.json")
                .then(response => response.json())
                .then(data => {
                    const countrySelect = document.getElementById("countryOfOrigin");
                    data.countries.forEach(country => {
                        let option = document.createElement("option");
                        option.value = country.name; // Using country code as value
                        option.textContent = country.name; // Display country name
                        countrySelect.appendChild(option);
                    });
                })
                .catch(error => console.error("Error loading countries:", error));
        });

        // Display other input box on selection of purpose of visit select field
        document.getElementById('purposeSelect').addEventListener('change', function() {
            var otherPurposeField = document.getElementById('otherPurposeField');
            if (this.value === 'other') {
                otherPurposeField.classList.remove('d-none');
                otherPurposeField.setAttribute('required', 'true');
            } else {
                otherPurposeField.classList.add('d-none');
                otherPurposeField.removeAttribute('required');
            }
        });


        // Additional Information: Travelling Alone
        // Show/hide travel companions field based on radio button selection
        document.addEventListener("DOMContentLoaded", function() {
            const radioYes = document.getElementById("travellingAloneYes");
            const radioNo = document.getElementById("travellingAloneNo");
            const travelCompanionsGroup = document.getElementById("travelCompanionsGroup");
            const travelCompanions = document.getElementById("travelCompanions");

            function toggleTravelCompanions() {
                if (radioNo.checked) {
                    travelCompanionsGroup.classList.remove("d-none");
                    travelCompanionsGroup.style.display = ""; // Remove inline style
                    travelCompanions.setAttribute("required", "true");
                } else {
                    travelCompanionsGroup.classList.add("d-none");
                    travelCompanionsGroup.style.display = "none"; // Add inline style
                    travelCompanions.removeAttribute("required");
                }
            }

            // Initialize visibility and required attribute based on pre-selected value
            toggleTravelCompanions();

            // Add event listeners to radio buttons
            radioYes.addEventListener("change", toggleTravelCompanions);
            radioNo.addEventListener("change", toggleTravelCompanions);
        });


        // Singapore Address
        // Preselect hotel input field visibility based on accommodation type
        document.addEventListener("DOMContentLoaded", function() {
            const accommodationSelect = document.getElementById("accommodation");
            const hotelNameGroup = document.getElementById("hotelNameGroup");

            function toggleHotelField() {
                if (accommodationSelect.value === "hotel") {
                    hotelNameGroup.style.display = "block";
                } else {
                    hotelNameGroup.style.display = "none";
                }
            }

            // Call toggleHotelField on page load to set the visibility based on preselected data
            toggleHotelField();

            // Add event listener for changes to accommodation selection
            accommodationSelect.addEventListener("change", toggleHotelField);
        });

        // passport descriptions
        const descriptions = {
            'Ordinary': 'A standard passport for regular citizens.',
            'Diplomatic': 'Issued to diplomats for international travel.',
            'Service': 'Issued to government employees for official duties.',
            'Official': 'For use by government employees for official purposes, not diplomatic.',
            'Special': 'Issued to certain individuals with special privileges.',
            'Temporary': 'For individuals who need a passport on a temporary basis.',
            'Nuclear': 'Special passports for nuclear-related personnel.'
        };

        function showPassportDescription() {
            const type = document.querySelector('select[name="passportType"]').value;
            document.getElementById('passportDescription').innerHTML = type ? `<p><strong>${type}:</strong> ${descriptions[type]}</p>` : '';
        }

        // Show description on page load if a passport type is pre-selected
        window.onload = showPassportDescription;

        // countries list for passport country of issue
        fetch('assets/countries.json')
            .then(response => response.json())
            .then(data => {
                const select = document.querySelector('select[name="countryOfIssue"]');
                const selectedCountry = '<?= htmlspecialchars($countryOfIssue); ?>'; // PHP value

                // Loop through countries and add them to the select element
                data.countries.forEach(country => {
                    const option = document.createElement('option');
                    option.value = country.code;
                    option.textContent = country.name;
                    if (country.code === selectedCountry) {
                        option.selected = true;
                    }
                    select.appendChild(option);
                });
            })
            .catch(error => console.error('Error loading countries:', error));

        // ante
        document.addEventListener("DOMContentLoaded", function() {
            const refusedEntrySwitch = document.getElementById('refusedEntrySwitch');
            const convictedSwitch = document.getElementById('convictedSwitch');
            const prohibitedEntrySwitch = document.getElementById('prohibitedEntrySwitch');
            const differentPassportSwitch = document.getElementById('differentPassportSwitch');
            const additionalDetailsContainer = document.getElementById('additionalDetailsContainer');

            // Function to toggle the Additional Details field visibility
            function toggleAdditionalDetails() {
                if (refusedEntrySwitch.checked || convictedSwitch.checked || prohibitedEntrySwitch.checked || differentPassportSwitch.checked) {
                    additionalDetailsContainer.style.display = 'block';
                } else {
                    additionalDetailsContainer.style.display = 'none';
                }
            }

            // Add event listeners to checkboxes
            refusedEntrySwitch.addEventListener('change', toggleAdditionalDetails);
            convictedSwitch.addEventListener('change', toggleAdditionalDetails);
            prohibitedEntrySwitch.addEventListener('change', toggleAdditionalDetails);
            differentPassportSwitch.addEventListener('change', toggleAdditionalDetails);

            // Initial check to set the visibility of Additional Details based on current checkbox states
            toggleAdditionalDetails();
        });
    </script>

<?php endif; ?>


</body>

</html>