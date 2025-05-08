<?php
// Check if decryptedId is set and not empty
if (!isset($decryptedId) || empty($decryptedId)) {
  echo "Error: Decrypted ID is missing.";
  exit;
}

$occupation_education = $database->get('occupation_education', '*', [
  'traveler_id' => $decryptedId,
  'order_id' => $order_id
]);


// Safe htmlspecialchars with null check
function safeHtmlspecialchars($value)
{
  return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

// Get values from the database with null checks
$occupation = getValue($occupation_education, 'occupation');
$annualIncome = getValue($occupation_education, 'annual_income');
$email = getValue($occupation_education, 'email');
$contactNumber = getValue($occupation_education, 'contact_number');
$highestQualification = getValue($occupation_education, 'highest_qualification');
?>
<?php
$countries = [
  ["name" => "Afghanistan", "dial_code" => "+93", "flag" => "🇦🇫"],
  ["name" => "Åland Islands", "dial_code" => "+358", "flag" => "🇦🇽"],
  ["name" => "Albania", "dial_code" => "+355", "flag" => "🇦🇱"],
  ["name" => "Algeria", "dial_code" => "+213", "flag" => "🇩🇿"],
  ["name" => "American Samoa", "dial_code" => "+1684", "flag" => "🇦🇸"],
  ["name" => "Andorra", "dial_code" => "+376", "flag" => "🇦🇩"],
  ["name" => "Angola", "dial_code" => "+244", "flag" => "🇦🇴"],
  ["name" => "Anguilla", "dial_code" => "+1264", "flag" => "🇦🇮"],
  ["name" => "Antarctica", "dial_code" => "+672", "flag" => "🇦🇶"],
  ["name" => "Antigua and Barbuda", "dial_code" => "+1268", "flag" => "🇦🇬"],
  ["name" => "Argentina", "dial_code" => "+54", "flag" => "🇦🇷"],
  ["name" => "Armenia", "dial_code" => "+374", "flag" => "🇦🇲"],
  ["name" => "Aruba", "dial_code" => "+297", "flag" => "🇦🇼"],
  ["name" => "Australia", "dial_code" => "+61", "flag" => "🇦🇺"],
  ["name" => "Austria", "dial_code" => "+43", "flag" => "🇦🇹"],
  ["name" => "Azerbaijan", "dial_code" => "+994", "flag" => "🇦🇿"],
  ["name" => "Bahamas", "dial_code" => "+1242", "flag" => "🇧🇸"],
  ["name" => "Bahrain", "dial_code" => "+973", "flag" => "🇧🇭"],
  ["name" => "Bangladesh", "dial_code" => "+880", "flag" => "🇧🇩"],
  ["name" => "Barbados", "dial_code" => "+1246", "flag" => "🇧🇧"],
  ["name" => "Belarus", "dial_code" => "+375", "flag" => "🇧🇾"],
  ["name" => "Belgium", "dial_code" => "+32", "flag" => "🇧🇪"],
  ["name" => "Belize", "dial_code" => "+501", "flag" => "🇧🇿"],
  ["name" => "Benin", "dial_code" => "+229", "flag" => "🇧🇯"],
  ["name" => "Bermuda", "dial_code" => "+1441", "flag" => "🇧🇲"],
  ["name" => "Bhutan", "dial_code" => "+975", "flag" => "🇧🇹"],
  ["name" => "Bolivia", "dial_code" => "+591", "flag" => "🇧🇴"],
  ["name" => "Bosnia and Herzegovina", "dial_code" => "+387", "flag" => "🇧🇦"],
  ["name" => "Botswana", "dial_code" => "+267", "flag" => "🇧🇼"],
  ["name" => "Brazil", "dial_code" => "+55", "flag" => "🇧🇷"],
  ["name" => "British Indian Ocean Territory", "dial_code" => "+246", "flag" => "🇮🇴"],
  ["name" => "Brunei Darussalam", "dial_code" => "+673", "flag" => "🇧🇳"],
  ["name" => "Bulgaria", "dial_code" => "+359", "flag" => "🇧🇬"],
  ["name" => "Burkina Faso", "dial_code" => "+226", "flag" => "🇧🇫"],
  ["name" => "Burundi", "dial_code" => "+257", "flag" => "🇧🇮"],
  ["name" => "Cambodia", "dial_code" => "+855", "flag" => "🇰🇭"],
  ["name" => "Cameroon", "dial_code" => "+237", "flag" => "🇨🇲"],
  ["name" => "Canada", "dial_code" => "+1", "flag" => "🇨🇦"],
  ["name" => "Cape Verde", "dial_code" => "+238", "flag" => "🇨🇻"],
  ["name" => "Cayman Islands", "dial_code" => "+345", "flag" => "🇰🇾"],
  ["name" => "Central African Republic", "dial_code" => "+236", "flag" => "🇨🇫"],
  ["name" => "Chad", "dial_code" => "+235", "flag" => "🇹🇩"],
  ["name" => "Chile", "dial_code" => "+56", "flag" => "🇨🇱"],
  ["name" => "China", "dial_code" => "+86", "flag" => "🇨🇳"],
  ["name" => "Christmas Island", "dial_code" => "+61", "flag" => "🇨🇽"],
  ["name" => "Cocos (Keeling) Islands", "dial_code" => "+61", "flag" => "🇨🇨"],
  ["name" => "Colombia", "dial_code" => "+57", "flag" => "🇨🇴"],
  ["name" => "Comoros", "dial_code" => "+269", "flag" => "🇰🇲"],
  ["name" => "Congo", "dial_code" => "+242", "flag" => "🇨🇬"],
  ["name" => "Congo, Democratic Republic", "dial_code" => "+243", "flag" => "🇨🇩"],
  ["name" => "Cook Islands", "dial_code" => "+682", "flag" => "🇨🇰"],
  ["name" => "Costa Rica", "dial_code" => "+506", "flag" => "🇨🇷"],
  ["name" => "Côte d'Ivoire", "dial_code" => "+225", "flag" => "🇨🇮"],
  ["name" => "Croatia", "dial_code" => "+385", "flag" => "🇭🇷"],
  ["name" => "Cuba", "dial_code" => "+53", "flag" => "🇨🇺"],
  ["name" => "Curaçao", "dial_code" => "+599", "flag" => "🇨🇼"],
  ["name" => "Cyprus", "dial_code" => "+357", "flag" => "🇨🇾"],
  ["name" => "Czech Republic", "dial_code" => "+420", "flag" => "🇨🇿"],
  ["name" => "Denmark", "dial_code" => "+45", "flag" => "🇩🇰"],
  ["name" => "Djibouti", "dial_code" => "+253", "flag" => "🇩🇯"],
  ["name" => "Dominica", "dial_code" => "+1767", "flag" => "🇩🇲"],
  ["name" => "Dominican Republic", "dial_code" => "+1849", "flag" => "🇩🇴"],
  ["name" => "Ecuador", "dial_code" => "+593", "flag" => "🇪🇨"],
  ["name" => "Egypt", "dial_code" => "+20", "flag" => "🇪🇬"],
  ["name" => "El Salvador", "dial_code" => "+503", "flag" => "🇸🇻"],
  ["name" => "Equatorial Guinea", "dial_code" => "+240", "flag" => "🇬🇶"],
  ["name" => "Eritrea", "dial_code" => "+291", "flag" => "🇪🇷"],
  ["name" => "Estonia", "dial_code" => "+372", "flag" => "🇪🇪"],
  ["name" => "Ethiopia", "dial_code" => "+251", "flag" => "🇪🇹"],
  ["name" => "Falkland Islands", "dial_code" => "+500", "flag" => "🇫🇰"],
  ["name" => "Faroe Islands", "dial_code" => "+298", "flag" => "🇫🇴"],
  ["name" => "Fiji", "dial_code" => "+679", "flag" => "🇫🇯"],
  ["name" => "Finland", "dial_code" => "+358", "flag" => "🇫🇮"],
  ["name" => "France", "dial_code" => "+33", "flag" => "🇫🇷"],
  ["name" => "French Guiana", "dial_code" => "+594", "flag" => "🇬🇫"],
  ["name" => "French Polynesia", "dial_code" => "+689", "flag" => "🇵🇫"],
  ["name" => "French Southern Territories", "dial_code" => "+262", "flag" => "🇹🇫"],
  ["name" => "Gabon", "dial_code" => "+241", "flag" => "🇬🇦"],
  ["name" => "Gambia", "dial_code" => "+220", "flag" => "🇬🇲"],
  ["name" => "Georgia", "dial_code" => "+995", "flag" => "🇬🇪"],
  ["name" => "Germany", "dial_code" => "+49", "flag" => "🇩🇪"],
  ["name" => "Ghana", "dial_code" => "+233", "flag" => "🇬🇭"],
  ["name" => "Gibraltar", "dial_code" => "+350", "flag" => "🇬🇮"],
  ["name" => "Greece", "dial_code" => "+30", "flag" => "🇬🇷"],
  ["name" => "Greenland", "dial_code" => "+299", "flag" => "🇬🇱"],
  ["name" => "Grenada", "dial_code" => "+1473", "flag" => "🇬🇩"],
  ["name" => "Guadeloupe", "dial_code" => "+590", "flag" => "🇬🇵"],
  ["name" => "Guam", "dial_code" => "+1671", "flag" => "🇬🇺"],
  ["name" => "Guatemala", "dial_code" => "+502", "flag" => "🇬🇹"],
  ["name" => "Guernsey", "dial_code" => "+44", "flag" => "🇬🇬"],
  ["name" => "Guinea", "dial_code" => "+224", "flag" => "🇬🇳"],
  ["name" => "Guinea-Bissau", "dial_code" => "+245", "flag" => "🇬🇼"],
  ["name" => "Guyana", "dial_code" => "+592", "flag" => "🇬🇾"],
  ["name" => "Haiti", "dial_code" => "+509", "flag" => "🇭🇹"],
  ["name" => "Honduras", "dial_code" => "+504", "flag" => "🇭🇳"],
  ["name" => "Hong Kong", "dial_code" => "+852", "flag" => "🇭🇰"],
  ["name" => "Hungary", "dial_code" => "+36", "flag" => "🇭🇺"],
  ["name" => "Iceland", "dial_code" => "+354", "flag" => "🇮🇸"],
  ["name" => "India", "dial_code" => "+91", "flag" => "🇮🇳"],
  ["name" => "Indonesia", "dial_code" => "+62", "flag" => "🇮🇩"],
  ["name" => "Iran", "dial_code" => "+98", "flag" => "🇮🇷"],
  ["name" => "Iraq", "dial_code" => "+964", "flag" => "🇮🇶"],
  ["name" => "Ireland", "dial_code" => "+353", "flag" => "🇮🇪"],
  ["name" => "Isle of Man", "dial_code" => "+44", "flag" => "🇮🇲"],
  ["name" => "Israel", "dial_code" => "+972", "flag" => "🇮🇱"],
  ["name" => "Italy", "dial_code" => "+39", "flag" => "🇮🇹"],
  ["name" => "Jamaica", "dial_code" => "+1876", "flag" => "🇯🇲"],
  ["name" => "Japan", "dial_code" => "+81", "flag" => "🇯🇵"],
  ["name" => "Jersey", "dial_code" => "+44", "flag" => "🇯🇪"],
  ["name" => "Jordan", "dial_code" => "+962", "flag" => "🇯🇴"],
  ["name" => "Kazakhstan", "dial_code" => "+7", "flag" => "🇰🇿"],
  ["name" => "Kenya", "dial_code" => "+254", "flag" => "🇰🇪"],
  ["name" => "Kiribati", "dial_code" => "+686", "flag" => "🇰🇮"],
  ["name" => "Kosovo", "dial_code" => "+383", "flag" => "🇽🇰"],
  ["name" => "Kuwait", "dial_code" => "+965", "flag" => "🇰🇼"],
  ["name" => "Kyrgyzstan", "dial_code" => "+996", "flag" => "🇰🇬"],
  ["name" => "Laos", "dial_code" => "+856", "flag" => "🇱🇦"],
  ["name" => "Latvia", "dial_code" => "+371", "flag" => "🇱🇻"],
  ["name" => "Lebanon", "dial_code" => "+961", "flag" => "🇱🇧"],
  ["name" => "Lesotho", "dial_code" => "+266", "flag" => "🇱🇸"],
  ["name" => "Liberia", "dial_code" => "+231", "flag" => "🇱🇷"],
  ["name" => "Libya", "dial_code" => "+218", "flag" => "🇱🇾"],
  ["name" => "Liechtenstein", "dial_code" => "+423", "flag" => "🇱🇮"],
  ["name" => "Lithuania", "dial_code" => "+370", "flag" => "🇱🇹"],
  ["name" => "Luxembourg", "dial_code" => "+352", "flag" => "🇱🇺"],
  ["name" => "Macao", "dial_code" => "+853", "flag" => "🇲🇴"],
  ["name" => "Madagascar", "dial_code" => "+261", "flag" => "🇲🇬"],
  ["name" => "Malawi", "dial_code" => "+265", "flag" => "🇲🇼"],
  ["name" => "Malaysia", "dial_code" => "+60", "flag" => "🇲🇾"],
  ["name" => "Maldives", "dial_code" => "+960", "flag" => "🇲🇻"],
  ["name" => "Mali", "dial_code" => "+223", "flag" => "🇲🇱"],
  ["name" => "Malta", "dial_code" => "+356", "flag" => "🇲🇹"],
  ["name" => "Marshall Islands", "dial_code" => "+692", "flag" => "🇲🇭"],
  ["name" => "Martinique", "dial_code" => "+596", "flag" => "🇲🇶"],
  ["name" => "Mauritania", "dial_code" => "+222", "flag" => "🇲🇷"],
  ["name" => "Mauritius", "dial_code" => "+230", "flag" => "🇲🇺"],
  ["name" => "Mayotte", "dial_code" => "+262", "flag" => "🇾🇹"],
  ["name" => "Mexico", "dial_code" => "+52", "flag" => "🇲🇽"],
  ["name" => "Moldova", "dial_code" => "+373", "flag" => "🇲🇩"],
  ["name" => "Monaco", "dial_code" => "+377", "flag" => "🇲🇨"],
  ["name" => "Mongolia", "dial_code" => "+976", "flag" => "🇲🇳"],
  ["name" => "Montenegro", "dial_code" => "+382", "flag" => "🇲🇪"],
  ["name" => "Montserrat", "dial_code" => "+1664", "flag" => "🇲🇸"],
  ["name" => "Morocco", "dial_code" => "+212", "flag" => "🇲🇦"],
  ["name" => "Mozambique", "dial_code" => "+258", "flag" => "🇲🇿"],
  ["name" => "Myanmar", "dial_code" => "+95", "flag" => "🇲🇲"],
  ["name" => "Namibia", "dial_code" => "+264", "flag" => "🇳🇦"],
  ["name" => "Nauru", "dial_code" => "+674", "flag" => "🇳🇷"],
  ["name" => "Nepal", "dial_code" => "+977", "flag" => "🇳🇵"],
  ["name" => "Netherlands", "dial_code" => "+31", "flag" => "🇳🇱"],
  ["name" => "New Caledonia", "dial_code" => "+687", "flag" => "🇳🇨"],
  ["name" => "New Zealand", "dial_code" => "+64", "flag" => "🇳🇿"],
  ["name" => "Nicaragua", "dial_code" => "+505", "flag" => "🇳🇮"],
  ["name" => "Niger", "dial_code" => "+227", "flag" => "🇳🇪"],
  ["name" => "Nigeria", "dial_code" => "+234", "flag" => "🇳🇬"],
  ["name" => "Niue", "dial_code" => "+683", "flag" => "🇳🇺"],
  ["name" => "Norfolk Island", "dial_code" => "+672", "flag" => "🇳🇫"],
  ["name" => "North Korea", "dial_code" => "+850", "flag" => "🇰🇵"],
  ["name" => "North Macedonia", "dial_code" => "+389", "flag" => "🇲🇰"],
  ["name" => "Northern Mariana Islands", "dial_code" => "+1670", "flag" => "🇲🇵"],
  ["name" => "Norway", "dial_code" => "+47", "flag" => "🇳🇴"],
  ["name" => "Oman", "dial_code" => "+968", "flag" => "🇴🇲"],
  ["name" => "Pakistan", "dial_code" => "+92", "flag" => "🇵🇰"],
  ["name" => "Palau", "dial_code" => "+680", "flag" => "🇵🇼"],
  ["name" => "Palestinian Territory", "dial_code" => "+970", "flag" => "🇵🇸"],
  ["name" => "Panama", "dial_code" => "+507", "flag" => "🇵🇦"],
  ["name" => "Papua New Guinea", "dial_code" => "+675", "flag" => "🇵🇬"],
  ["name" => "Paraguay", "dial_code" => "+595", "flag" => "🇵🇾"],
  ["name" => "Peru", "dial_code" => "+51", "flag" => "🇵🇪"],
  ["name" => "Philippines", "dial_code" => "+63", "flag" => "🇵🇭"],
  ["name" => "Pitcairn", "dial_code" => "+64", "flag" => "🇵🇳"],
  ["name" => "Poland", "dial_code" => "+48", "flag" => "🇵🇱"],
  ["name" => "Portugal", "dial_code" => "+351", "flag" => "🇵🇹"],
  ["name" => "Puerto Rico", "dial_code" => "+1939", "flag" => "🇵🇷"],
  ["name" => "Qatar", "dial_code" => "+974", "flag" => "🇶🇦"],
  ["name" => "Romania", "dial_code" => "+40", "flag" => "🇷🇴"],
  ["name" => "Russia", "dial_code" => "+7", "flag" => "🇷🇺"],
  ["name" => "Rwanda", "dial_code" => "+250", "flag" => "🇷🇼"],
  ["name" => "Réunion", "dial_code" => "+262", "flag" => "🇷🇪"],
  ["name" => "Saint Barthélemy", "dial_code" => "+590", "flag" => "🇧🇱"],
  ["name" => "Saint Helena, Ascension and Tristan da Cunha", "dial_code" => "+290", "flag" => "🇸🇭"],
  ["name" => "Saint Kitts and Nevis", "dial_code" => "+1869", "flag" => "🇰🇳"],
  ["name" => "Saint Lucia", "dial_code" => "+1758", "flag" => "🇱🇨"],
  ["name" => "Saint Martin", "dial_code" => "+590", "flag" => "🇲🇫"],
  ["name" => "Saint Pierre and Miquelon", "dial_code" => "+508", "flag" => "🇵🇲"],
  ["name" => "Saint Vincent and the Grenadines", "dial_code" => "+1784", "flag" => "🇻🇨"],
  ["name" => "Samoa", "dial_code" => "+685", "flag" => "🇼🇸"],
  ["name" => "San Marino", "dial_code" => "+378", "flag" => "🇸🇲"],
  ["name" => "Sao Tome and Principe", "dial_code" => "+239", "flag" => "🇸🇹"],
  ["name" => "Saudi Arabia", "dial_code" => "+966", "flag" => "🇸🇦"],
  ["name" => "Senegal", "dial_code" => "+221", "flag" => "🇸🇳"],
  ["name" => "Serbia", "dial_code" => "+381", "flag" => "🇷🇸"],
  ["name" => "Seychelles", "dial_code" => "+248", "flag" => "🇸🇨"],
  ["name" => "Sierra Leone", "dial_code" => "+232", "flag" => "🇸🇱"],
  ["name" => "Singapore", "dial_code" => "+65", "flag" => "🇸🇬"],
  ["name" => "Sint Maarten", "dial_code" => "+1721", "flag" => "🇸🇽"],
  ["name" => "Slovakia", "dial_code" => "+421", "flag" => "🇸🇰"],
  ["name" => "Slovenia", "dial_code" => "+386", "flag" => "🇸🇮"],
  ["name" => "Solomon Islands", "dial_code" => "+677", "flag" => "🇸🇧"],
  ["name" => "Somalia", "dial_code" => "+252", "flag" => "🇸🇴"],
  ["name" => "South Africa", "dial_code" => "+27", "flag" => "🇿🇦"],
  ["name" => "South Georgia and the South Sandwich Islands", "dial_code" => "+500", "flag" => "🇬🇸"],
  ["name" => "South Korea", "dial_code" => "+82", "flag" => "🇰🇷"],
  ["name" => "South Sudan", "dial_code" => "+211", "flag" => "🇸🇸"],
  ["name" => "Spain", "dial_code" => "+34", "flag" => "🇪🇸"],
  ["name" => "Sri Lanka", "dial_code" => "+94", "flag" => "🇱🇰"],
  ["name" => "Sudan", "dial_code" => "+249", "flag" => "🇸🇩"],
  ["name" => "Suriname", "dial_code" => "+597", "flag" => "🇸🇷"],
  ["name" => "Svalbard and Jan Mayen", "dial_code" => "+47", "flag" => "🇸🇯"],
  ["name" => "Sweden", "dial_code" => "+46", "flag" => "🇸🇪"],
  ["name" => "Switzerland", "dial_code" => "+41", "flag" => "🇨🇭"],
  ["name" => "Syria", "dial_code" => "+963", "flag" => "🇸🇾"],
  ["name" => "Taiwan", "dial_code" => "+886", "flag" => "🇹🇼"],
  ["name" => "Tajikistan", "dial_code" => "+992", "flag" => "🇹🇯"],
  ["name" => "Tanzania", "dial_code" => "+255", "flag" => "🇹🇿"],
  ["name" => "Thailand", "dial_code" => "+66", "flag" => "🇹🇭"],
  ["name" => "Togo", "dial_code" => "+228", "flag" => "🇹🇬"],
  ["name" => "Tokelau", "dial_code" => "+690", "flag" => "🇹🇰"],
  ["name" => "Tonga", "dial_code" => "+676", "flag" => "🇹🇴"],
  ["name" => "Trinidad and Tobago", "dial_code" => "+1868", "flag" => "🇹🇹"],
  ["name" => "Tunisia", "dial_code" => "+216", "flag" => "🇹🇳"],
  ["name" => "Turkey", "dial_code" => "+90", "flag" => "🇹🇷"],
  ["name" => "Turkmenistan", "dial_code" => "+993", "flag" => "🇹🇲"],
  ["name" => "Turks and Caicos Islands", "dial_code" => "+1649", "flag" => "🇹🇨"],
  ["name" => "Tuvalu", "dial_code" => "+688", "flag" => "🇹🇻"],
  ["name" => "Uganda", "dial_code" => "+256", "flag" => "🇺🇬"],
  ["name" => "Ukraine", "dial_code" => "+380", "flag" => "🇺🇦"],
  ["name" => "United Arab Emirates", "dial_code" => "+971", "flag" => "🇦🇪"],
  ["name" => "United Kingdom", "dial_code" => "+44", "flag" => "🇬🇧"],
  ["name" => "United States", "dial_code" => "+1", "flag" => "🇺🇸"],
  ["name" => "United States Minor Outlying Islands", "dial_code" => "+1", "flag" => "🇺🇲"],
  ["name" => "Uruguay", "dial_code" => "+598", "flag" => "🇺🇾"],
  ["name" => "Uzbekistan", "dial_code" => "+998", "flag" => "🇺🇿"],
  ["name" => "Vanuatu", "dial_code" => "+678", "flag" => "🇻🇺"],
  ["name" => "Vatican City", "dial_code" => "+379", "flag" => "🇻🇦"],
  ["name" => "Venezuela", "dial_code" => "+58", "flag" => "🇻🇪"],
  ["name" => "Vietnam", "dial_code" => "+84", "flag" => "🇻🇳"],
  ["name" => "Virgin Islands, British", "dial_code" => "+1284", "flag" => "🇻🇬"],
  ["name" => "Virgin Islands, U.S.", "dial_code" => "+1340", "flag" => "🇻🇮"],
  ["name" => "Wallis and Futuna", "dial_code" => "+681", "flag" => "🇼🇫"],
  ["name" => "Western Sahara", "dial_code" => "+212", "flag" => "🇪🇭"],
  ["name" => "Yemen", "dial_code" => "+967", "flag" => "🇾🇪"],
  ["name" => "Zambia", "dial_code" => "+260", "flag" => "🇿🇲"],
  ["name" => "Zimbabwe", "dial_code" => "+263", "flag" => "🇿🇼"]
];
?>

<div class="card mb-3">
  <div class="card-header">
    <h5 class="mb-0">
      <i class="bi bi-briefcase me-2"></i> Occupation and Education
    </h5>
  </div>
  <div class="card-body">
    <div class="row g-3">
      <!-- Occupation -->
      <div class="col-md-6">
        <label class="form-label">Occupation</label>
        <select class="form-select" id="occupationSelect" name="occupationSelect" aria-label="Select occupation" required>
          <option value="" selected disabled>Select an occupation</option>
          <?php
          $occupations = [
            "agricultural_fishery" => "Agricultural or Fishery Worker",
            "businessman" => "Businessman",
            "child_infant" => "Child/Infant",
            "cleaner" => "Cleaner",
            "clerical" => "Clerical Worker",
            "housewife" => "Housewife",
            "labourer" => "Labourer",
            "legislator" => "Legislator",
            "machine_operator" => "Machine Operation or Assembler",
            "manager" => "Manager",
            "monk" => "Monk",
            "priest" => "Priest",
            "production" => "Production Worker",
            "professional" => "Professional",
            "proprietor" => "Proprietor",
            "religious_teacher" => "Religious Teacher",
            "retiree_seaman" => "Retiree Seaman",
            "seaman" => "Seaman",
            "senior_official" => "Senior Official",
            "service_worker" => "Service Worker",
            "student" => "Student",
            "technician" => "Technician",
            "unemployed" => "Unemployed",
            "others" => "Others"
          ];

          foreach ($occupations as $value => $label) {
            $selected = ($occupation == $value) ? 'selected' : '';
            echo "<option value=\"$value\" $selected>$label</option>";
          }
          ?>
        </select>
        <div class="valid-feedback">Looks good!</div>
        <div class="invalid-feedback">Please select an occupation.</div>
      </div>

      <!-- Annual Income -->
      <div class="col-md-6">
        <label class="form-label">Annual Income (SGD)</label>
        <input type="number" name="annualIncome" class="form-control" value="<?= safeHtmlspecialchars($annualIncome); ?>" required />
        <div class="valid-feedback">Looks good!</div>
        <div class="invalid-feedback">Please enter your annual income.</div>
      </div>

      <!-- Email Address -->
      <div class="col-md-6">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" value="<?= safeHtmlspecialchars($email); ?>" required />
        <div class="valid-feedback">Looks good!</div>
        <div class="invalid-feedback">Please enter a valid email address.</div>
      </div>

      <!-- Contact Number -->
      <div class="col-md-6">
        <label class="form-label">Contact Number</label>
        <div class="input-group">
          <div class="col-auto">
            <?php
            $fullPhoneNumber = (string) $contactNumber; // e.g. +918303095447
            $fullPhoneNumber = str_replace(' ', '', $fullPhoneNumber);

            preg_match('/^\+(?:(\d{4})|(\d{3})|(\d{2}))(\d{10})$/', $fullPhoneNumber, $matches);

            if (count($matches) > 0) {
              $countryCode = '';
              for ($i = 1; $i <= 3; $i++) {
                if (!empty($matches[$i])) {
                  $countryCode = $matches[$i];
                  break;
                }
              }
              $phoneNumber = $matches[4];
            } else {
              $countryCode = '';
              $phoneNumber = '';
            }
            ?>
            <select name="countryCode" class="form-select w-auto" style="max-width: 100px;" required>
              <option value="" selected disabled>Choose</option>
              <?php
              foreach ($countries as $country) {
                $selected = ($countryCode == $country['dial_code']) ? 'selected' : '';
                echo '<option value="' . $country['dial_code'] . '" ' . $selected . '>' . $country['flag'] . ' ' . $country['name'] . ' (' . $country['dial_code'] . ')</option>';
              }
              ?>
            </select>
          </div>
          <input type="tel" name="phoneNumber" class="form-control flex-grow-1" placeholder="Enter phone number" required pattern="\d{7,15}" value="<?= safeHtmlspecialchars($phoneNumber); ?>" />
          <div class="valid-feedback">Looks good!</div>
          <div class="invalid-feedback">Please enter a valid phone number.</div>
        </div>
      </div>

      <!-- Highest Qualification -->
      <div class="col-12">
        <label class="form-label">Highest Academic/Professional Qualifications</label>
        <input type="text" name="highestQualification" class="form-control" value="<?= safeHtmlspecialchars($highestQualification); ?>" required />
        <div class="valid-feedback">Looks good!</div>
        <div class="invalid-feedback">Please enter your highest qualification.</div>
      </div>

      <div class="col-12">
        <input type="hidden" name="traveler_id" id="traveler_id" value="<?= safeHtmlspecialchars($decryptedId); ?>">
        <button type="button" class="btn btn-outline-golden" onclick="saveDraft(4); return false;">Save Progress</button>
      </div>
    </div>
  </div>
</div>