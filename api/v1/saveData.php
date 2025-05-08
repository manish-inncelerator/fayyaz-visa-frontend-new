<?php
session_start();

require('../../database.php');
require 'functions/validate_hu.php';

header('Content-Type: application/json');

$uuid = $_SESSION['uuid'] ?? null;
// Fetch HU from headers
$headers = getallheaders();
$hu = $headers['HU'] ?? $headers['Hu'] ?? $headers['hu'] ?? null;
// Fetch order_id from headers
$order_id = $headers['X-Order-Id'] ?? null;

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = parse_url($scheme . '://' . ($_SERVER['HTTP_HOST'] ?? ''), PHP_URL_HOST);

if (!isValidRequest($uuid, $hu, $host)) {
    respondWithJson(["error" => "Invalid request", "debug" => getErrorDetails($uuid, $hu, $host)], 400);
}

if (!isHuValid($uuid, $hu)) {
    respondWithJson(["error" => "Invalid HU"], 401);
}

// Get JSON input
$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

// print_r($data);

// Sanitize all data fields individually
if (is_array($data)) {
    foreach ($data as $key => $value) {
        $data[$key] = sanitizeInput($value);
    }
}

$step = $data['step'] ?? null;

try {
    switch ($step) {
        case 1:
            savePersonalInformation($database, $data, $order_id);
            break;
        case 2:
            validateTravelerId($data);
            saveTravelDocument($database, $data, $order_id);
            break;
        case 3:
            validateTravelerId($data);
            saveAddressInformation($database, $data, $order_id);
            break;
        case 4:
            validateTravelerId($data);
            saveOccupationEducation($database, $data, $order_id);
            break;
        case 5:
            validateTravelerId($data);
            saveVisitInformation($database, $data, $order_id);
            break;
        case 6:
            validateTravelerId($data);
            saveAdditionalInformation($database, $data, $order_id);
            break;
        case 7:
            validateTravelerId($data);
            saveAntecedentInformation($database, $data, $order_id);
            break;
        case 8:
            validateTravelerId($data);
            saveDeclarations($database, $data, $order_id);
            break;
        case 9:
            validateTravelerId($data);
            saveSingaporeAddress($database, $data, $order_id);
            break;
        default:
            http_response_code(400);
            echo json_encode(['message' => 'Invalid step']);
            exit;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => 'An error occurred', 'error' => $e->getMessage()]);
}

// Helper function to update or insert data
function updateOrInsert($database, $table, $where, $data)
{
    $data['created_at'] = isset($data['created_at']) ? $data['created_at'] : date('Y-m-d H:i:s'); // Set the current timestamp if not provided
    // Always include order_id in both where and data
    $existing = $database->get($table, '*', $where);
    if ($existing) {
        // Update the created_at timestamp when updating a record
        $data['created_at'] = date('Y-m-d H:i:s'); // Update the created_at timestamp
        $database->update($table, array_merge($data, ['order_id' => $data['order_id']]), $where);
        echo json_encode(['message' => ucfirst(str_replace('_', ' ', $table)) . ' updated successfully']);
    } else {
        // For insert, include order_id in the data
        $database->insert($table, array_merge($where, $data));
        echo json_encode(['message' => ucfirst(str_replace('_', ' ', $table)) . ' inserted successfully']);
    }
}

// Save personal information
function savePersonalInformation($database, $data, $order_id)
{
    updateOrInsert($database, 'applicants', ['traveler_id' => $data['traveler_id']], [
        'full_name' => $data['fullName'] ?? null,
        'date_of_birth' => $data['dob'] ?? null,
        'gender' => $data['gender'] ?? null,
        'race' => $data['raceSelect'] ?? null,
        'country_of_birth' => $data['countrySelect'] ?? null,
        'nationality' => $data['nationalitySelect'] ?? null,
        'religion' => $data['religionSelect'] ?? null,
        'marital_status' => $data['maritalStatus'] ?? null,
        'spouse_nationality' => $data['spouseNationality'] ?? null,
        'order_id' => $order_id
    ]);
}

// Save travel document details
function saveTravelDocument($database, $data, $order_id)
{
    updateOrInsert($database, 'travel_documents', ['traveler_id' => $data['traveler_id']], [
        'passport_type' => $data['passportType'] ?? null,
        'country_of_issue' => $data['countryOfIssue'] ?? null,
        'place_of_issue' => $data['placeOfIssue'] ?? null,
        'passport_number' => $data['passportNumber'] ?? null,
        'issue_date' => $data['issueDate'] ?? null,
        'expiry_date' => $data['expiryDate'] ?? null,
        'order_id' => $order_id
    ]);
}

// Save address details
function saveAddressInformation($database, $data, $order_id)
{
    updateOrInsert($database, 'addresses', ['traveler_id' => $data['traveler_id']], [
        'country_of_origin' => $data['countryOfOrigin'] ?? null,
        'permanent_address' => $data['permanentAddress'] ?? null,
        'order_id' => $order_id
    ]);
}

// Save occupation and education details
function saveOccupationEducation($database, $data, $order_id)
{
    $contactNumber = $data['countryCode'] . $data['phoneNumber'];

    updateOrInsert($database, 'occupation_education', ['traveler_id' => $data['traveler_id']], [
        'occupation' => $data['occupationSelect'] ?? null,
        'annual_income' => $data['annualIncome'] ?? null,
        'email' => filter_var($data['email'] ?? null, FILTER_SANITIZE_EMAIL),
        'contact_number' => $contactNumber,
        'highest_qualification' => $data['highestQualification'] ?? null,
        'order_id' => $order_id
    ]);
}

// Save additional information
function saveAdditionalInformation($database, $data, $order_id)
{
    $existingRecord = $database->select(
        'additional_information',
        ['travelling_alone', 'travel_companions'],
        ['traveler_id' => $data['traveler_id']]
    );

    if ($existingRecord && $existingRecord[0]['travelling_alone'] !== $data['travellingAlone']) {
        if ($data['travellingAlone'] === 'yes') {
            $data['travelCompanions'] = null;
        }
    }

    updateOrInsert($database, 'additional_information', ['traveler_id' => $data['traveler_id']], [
        'travelling_alone' => $data['travellingAlone'] ?? null,
        'travel_companions' => $data['travelCompanions'] ?? null,
        'order_id' => $order_id
    ]);
}

// Save visit information
function saveVisitInformation($database, $data, $order_id)
{
    $otherReason = null;
    if (!empty($data['otherPurpose']) && $data['purposeOfVisit'] === 'other') {
        $otherReason = $data['otherPurpose'];
    }

    updateOrInsert($database, 'visit_information', ['traveler_id' => $data['traveler_id']], [
        'arrival_date' => $data['arrivalDate'] ?? null,
        'inflight_number' => $data['inflightNumber'] ?? null,
        'visa_type' => $data['visaType'] ?? null,
        'stay_duration' => $data['stayDuration'] ?? null,
        'departure_date' => $data['departureDate'] ?? null,
        'outflight_number' => $data['outflightNumber'] ?? null,
        'purpose_of_visit' => $data['purposeOfVisit'] ?? null,
        'other_purpose' => $otherReason,
        'order_id' => $order_id
    ]);
}

// Save antecedent details
function saveAntecedentInformation($database, $data, $order_id)
{
    updateOrInsert(
        $database,
        'antecedent_information',
        ['traveler_id' => $data['traveler_id']],
        [
            'resided_abroad' => !empty($data['residedAbroad']) ? $data['residedAbroad'] : null,
            'refused_entry' => isset($data['refusedEntry']) ? $data['refusedEntry'] : null,
            'convicted' => isset($data['convicted']) ? $data['convicted'] : null,
            'prohibited' => isset($data['prohibitedEntry']) ? $data['prohibitedEntry'] : null,
            'different_passport' => isset($data['differentPassport']) ? $data['differentPassport'] : null,
            'antecedent_details' => !empty($data['additionalDetails']) ? $data['additionalDetails'] : null,
            'travelling_from_country' => !empty($data['travellingFrom']) ? $data['travellingFrom'] : null,
            'order_id' => $order_id
        ]
    );
}

// Save Singapore address
function saveSingaporeAddress($database, $data, $order_id)
{
    updateOrInsert(
        $database,
        'addresses',
        ['traveler_id' => $data['traveler_id']],
        [
            'singapore_address' => !empty($data['accommodation']) ? $data['accommodation'] : null,
            'hotel_name' => isset($data['hotelName']) ? $data['hotelName'] : null,
            'order_id' => $order_id
        ]
    );
}
// Save declarations
function saveDeclarations($database, $data, $order_id)
{
    $declaration_agreed = $data['declaration_agreed'] ?? '';
    // Ensure boolean checkboxes have a default value if not provided
    $declaration_agreed = $declaration_agreed;
    $responsibility_for_errors = isset($data['responsibility_for_errors']) ? 'yes' : 'no';
    $untrue_information_penalty = isset($data['untrue_information_penalty']) ? 'yes' : 'no';
    $submission_verification = isset($data['submission_verification']) ? 'yes' : 'no';
    $fraudulent_application_suspension = isset($data['fraudulent_application_suspension']) ? 'yes' : 'no';
    $deposit_withdrawal = isset($data['deposit_withdrawal']) ? 'yes' : 'no';
    $information_verification = isset($data['information_verification']) ? 'yes' : 'no';
    $applicant_responsibility = isset($data['applicant_responsibility']) ? 'yes' : 'no';
    $land_packages_interest = isset($data['land_packages_interest']) ? 'yes' : 'no';


    // Handle the insert or update operation
    updateOrInsert($database, 'declaration_terms', ['order_id' => $order_id], [
        'traveler_id' => $data['traveler_id'] ?? null,
        'declaration_agreed' => $declaration_agreed,
        'responsibility_for_errors' => $responsibility_for_errors,
        'untrue_information_penalty' => $untrue_information_penalty,
        'submission_verification' => $submission_verification,
        'fraudulent_application_suspension' => $fraudulent_application_suspension,
        'deposit_withdrawal' => $deposit_withdrawal,
        'information_verification' => $information_verification,
        'applicant_responsibility' => $applicant_responsibility,
        'is_finished' => 1,
        'land_packages_interest' => $land_packages_interest, // default to current timestamp if not provided
        'order_id' => $order_id
    ]);
}


// Validate traveler ID
function validateTravelerId($data)
{
    if (empty($data['traveler_id'])) {
        http_response_code(400);
        echo json_encode(['message' => 'Traveler ID is required for this step']);
        exit;
    }
}
