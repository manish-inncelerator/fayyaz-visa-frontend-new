<?php

// Associating icons with rejection reasons
$rejectionIcons = [
    'Expired Passport' => 'bi-passport', // Icon for expired passport
    'Handwritten Passports' => 'bi-pencil-square', // Icon for handwritten passports
    'Criminal Record' => 'bi-exclamation-square', // Icon for criminal record
    'Previous Visa Violations' => 'bi-exclamation-triangle', // Icon for previous visa violations
    'Incomplete Documentation' => 'bi-file-earmark-x', // Icon for incomplete documentation
    'Medical Fitness Issues' => 'bi-heart-pulse', // Icon for medical fitness issues
    'Insufficient Financial Proof' => 'bi-cash-coin', // Icon for insufficient financial proof
    'Lack of Travel Itinerary' => 'bi-calendar-x', // Icon for lack of travel itinerary
    'Poor Academic Records' => 'bi-mortarboard', // Icon for poor academic records
    'Lack of Ties to Home Country' => 'bi-house-x', // Icon for lack of ties to home country
    'Lack of Genuine Temporary Entrant Intent' => 'bi-person-x', // Icon for lack of genuine temporary entrant intent
    'Inaccurate Information' => 'bi-info-circle', // Icon for inaccurate information
    'High Visa Costs' => 'bi-currency-dollar', // Icon for high visa costs
    'Stringent Immigration Policies' => 'bi-shield-lock', // Icon for stringent immigration policies
    'Previous Visa Rejections' => 'bi-x-circle' // Icon for previous visa rejections
];

// Default icon for any reason not in the list
$defaultIcon = 'bi-exclamation-circle'; // Default icon if no specific icon is found


$visaIssues = [
    'Philippines' => [
        'Incomplete or Inaccurate Documentation', // Missing or incorrect documents can lead to application denial.
        'Insufficient Financial Proof', // Applicants must demonstrate adequate financial resources for their stay.
        'Lack of Clear Travel Itinerary or Purpose of Visit', // A well-defined travel plan or reason for visiting is crucial.
        'Previous Visa Violations or Overstays', // Past visa issues can negatively impact new applications.
        'Lack of Ties to Home Country', // Strong connections to the home country are essential to assure authorities of the applicant's intent to return.
    ],
    'India' => [
        'Incomplete or Inaccurate Documentation', // Missing or incorrect documents can lead to application denial.
        'Insufficient Financial Proof', // Applicants must provide evidence of sufficient funds for the duration of their stay.
        'Lack of Clear Travel Itinerary or Purpose of Visit', // A well-defined travel plan is necessary to justify the visit.
        'Previous Visa Violations or Overstays', // Past visa issues can raise concerns for new applications.
        'Lack of Ties to Home Country', // Demonstrating strong connections to the home country is vital for visa approval.
    ],
    'United Kingdom' => [
        'Blurred or Low Quality Photograph', // A photograph that does not meet UKVI specifications (e.g., size, clarity, background) can lead to application rejection.
        'Stringent Immigration Policies', // The UK has strict immigration rules that applicants must navigate.
        'Incomplete or Inaccurate Documentation', // Missing or incorrect paperwork can lead to rejection.
        'Insufficient Financial Proof', // Applicants must show they can financially support themselves during their stay.
        'Lack of Ties to Home Country', // Demonstrating strong connections to the home country is vital for visa approval.
    ],
    'Singapore' => [
        'Incomplete or Incorrect Documentation', // Proper documentation is critical for a successful application.
        'Insufficient Financial Proof', // Applicants need to provide proof of adequate financial resources.
        'Lack of Travel Itinerary', // A clear travel plan is necessary to justify the visit.
        'Previous Visa Rejections', // Past rejections can negatively affect new applications.
        'Criminal Record', // Any criminal history may lead to visa denial.
    ],
    'Australia' => [
        'Insufficient Financial Evidence', // Applicants must demonstrate they have enough funds for their stay.
        'Incomplete or Incorrect Documentation', // Proper documentation is essential for a successful application.
        'Lack of Genuine Temporary Entrant Intent', // Applicants must prove their intent to visit temporarily.
        'Inadequate English Language Proficiency', // Language skills may be assessed as part of the application process.
        'Poor Academic Records', // Academic performance can be a factor in visa eligibility.
    ],
    'United Arab Emirates' => [
        'Expired Passport', // A valid passport is crucial for visa approval.
        'Criminal Record', // Any criminal history may lead to visa denial.
        'Previous Visa Violations', // Past issues with visas can negatively impact future applications.
        'Incomplete or Incorrect Documentation', // Proper documentation is critical for a successful application.
        'Medical Fitness Issues', // Health-related concerns may affect visa eligibility.
    ],
    'United States Of America' => [
        'Incomplete Application', // All sections of the application must be filled out correctly.
        'Incorrect or Inaccurate Information', // Providing false information can lead to denial.
        'Insufficient Financial Support', // Applicants must show they can financially support themselves during their stay.
        'Lack of Strong Ties to Home Country', // Strong connections to the home country are essential to assure authorities of the applicant's intent to return.
    ],
    'France' => [
        'Incomplete or Inaccurate Documentation', // Missing or incorrect documents can lead to application denial.
        'Insufficient Financial Proof', // Applicants must demonstrate adequate financial resources for their stay.
        'Lack of Clear Travel Itinerary', // A well-defined travel plan is necessary to justify the visit.
        'Previous Visa Violations', // Past visa issues can negatively impact new applications.
        'Medical Fitness Issues', // Health-related concerns may affect visa eligibility.
    ],
];


$visaDetails = [
    'country_name' => $country_name // Storing the country name for display
];

// Match country from URL and display reasons 
echo '<h2 class="heading-underline fw-bold mt-3">' . htmlspecialchars($visaDetails['country_name']) . " Visa Rejection Reasons</h2>"; // Displaying the heading
echo '<p>Factors that can get your visa rejected</p>'; // Displaying introductory text

if (array_key_exists($country_name, $visaIssues)) { // Check if the country has specific visa issues
    echo '<ul class="list-group">'; // Start of the list
    foreach ($visaIssues[$country_name] as $rejectionReason) { // Loop through each rejection reason
        // Find an appropriate icon based on the reason text
        $iconClass = $defaultIcon; // Initialize icon class with default icon
        foreach ($rejectionIcons as $key => $icon) { // Loop through defined icons
            if (stripos($rejectionReason, $key) !== false) { // Check if the rejection reason matches any key
                $iconClass = $icon; // Set the icon class to the matched icon
                break; // Exit the loop once a match is found
            }
        }

        // Display the rejection reason with its icon
        echo '<li class="list-group-item" style="border: 1px solid var(--blue);' . ($rejectionReason === end($visaIssues[$country_name]) ? '' : ' border-bottom: none;') . '"><i class="bi ' . $iconClass . '"></i> ' . htmlspecialchars($rejectionReason) . '</li>';
    }
    echo '</ul>'; // End of the list
} else {
    echo '<p>No visa rejection reasons found for ' . htmlspecialchars($country_name) . '.</p>'; // Message if no reasons are found
}
