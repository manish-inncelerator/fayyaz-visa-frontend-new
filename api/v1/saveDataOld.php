<?php
session_start();

require('../../database.php');
require 'functions/validate_hu.php';

header('Content-Type: application/json');

$uuid = $_SESSION['uuid'] ?? null;
// Fetch HU from headers
$headers = getallheaders();
$hu = $headers['HU'] ?? $headers['Hu'] ?? $headers['hu'] ?? null;

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = parse_url($scheme . '://' . ($_SERVER['HTTP_HOST'] ?? ''), PHP_URL_HOST);
if (DEV_MODE) {
    echo json_encode(["debug" => ["uuid" => $uuid, "hu" => $hu, "host" => $host]]);
}

if (!isValidRequest($uuid, $hu, $host)) {
    respondWithJson(["error" => "Invalid request", "debug" => getErrorDetails($uuid, $hu, $host)], 400);
}

if (!isHuValid($uuid, $hu)) {
    respondWithJson(["error" => "Invalid HU"], 401);
}
// MAIN CODE
// Get JSON input
$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

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
            savePersonalInformation($database, $data);
            break;
        case 2:
            validateTravelerId($data);
            saveTravelDocument($database, $data);
            break;
        case 3:
            validateTravelerId($data);
            saveAddressInformation($database, $data);
            break;
        case 4:
            validateTravelerId($data);
            saveOccupationEducation($database, $data);
            break;
        case 5:
            validateTravelerId($data);
            saveVisitInformation($database, $data);
            break;
        case 6:
            validateTravelerId($data);
            saveAdditionalInformation($database, $data);
            break;
        case 7:
            validateTravelerId($data);
            saveAntecedentInformation($database, $data);
            break;
        case 8:
            validateTravelerId($data);
            saveDeclarations($database, $data);
            break;
        case 9:
            validateTravelerId($data);
            saveSingaporeAddress($database, $data);
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
    $existing = $database->get($table, '*', $where);
    if ($existing) {
        $database->update($table, $data, $where);
        echo json_encode(['message' => ucfirst(str_replace('_', ' ', $table)) . ' updated successfully']);
    } else {
        $database->insert($table, array_merge($where, $data));
        echo json_encode(['message' => ucfirst(str_replace('_', ' ', $table)) . ' inserted successfully']);
    }
}

// Save personal information
function savePersonalInformation($database, $data)
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
    ]);
}

// Save travel document details
function saveTravelDocument($database, $data)
{
    updateOrInsert($database, 'travel_documents', ['traveler_id' => $data['traveler_id']], [
        'passport_type' => $data['passportType'] ?? null,
        'country_of_issue' => $data['countryOfIssue'] ?? null,
        'place_of_issue' => $data['placeOfIssue'] ?? null,
        'passport_number' => $data['passportNumber'] ?? null,
        'issue_date' => $data['issueDate'] ?? null,
        'expiry_date' => $data['expiryDate'] ?? null,
    ]);
}

// Save address details
function saveAddressInformation($database, $data)
{
    updateOrInsert($database, 'addresses', ['traveler_id' => $data['traveler_id']], [
        'country_of_origin' => $data['countryOfOrigin'] ?? null,
        'permanent_address' => $data['permanentAddress'] ?? null
    ]);
}

// Save occupation and education details
function saveOccupationEducation($database, $data)
{
    $contactNumber = $data['countryCode'] . $data['phoneNumber'];

    updateOrInsert($database, 'occupation_education', ['traveler_id' => $data['traveler_id']], [
        'occupation' => $data['occupationSelect'] ?? null,
        'annual_income' => $data['annualIncome'] ?? null,
        'email' => filter_var($data['email'] ?? null, FILTER_SANITIZE_EMAIL),
        'contact_number' => $contactNumber, // Fixed key reference
        'highest_qualification' => $data['highestQualification'] ?? null,
    ]);
}

// Save additional information (travelling alone and travel companions)
function saveAdditionalInformation($database, $data)
{
    // Get the current record to check if the 'travelling_alone' value is changing
    $existingRecord = $database->select('additional_information', ['travelling_alone', 'travel_companions'], ['traveler_id' => $data['traveler_id']]);

    // If the traveller is changing 'travelling_alone' from 'no' to 'yes', set 'travel_companions' to null
    if ($existingRecord && $existingRecord[0]['travelling_alone'] !== $data['travellingAlone']) {
        if ($data['travellingAlone'] === 'yes') {
            // If 'yes' is selected, clear travel companions
            $data['travelCompanions'] = null;
        }
    }

    // Update or insert the data
    updateOrInsert($database, 'additional_information', ['traveler_id' => $data['traveler_id']], [
        'travelling_alone' => $data['travellingAlone'] ?? null,
        'travel_companions' => $data['travelCompanions'] ?? null, // Optional field
    ]);
}

// Save visit information
function saveVisitInformation($database, $data)
{
    // Default value for otherPurpose to avoid undefined variable error
    $otherReason = null;

    // Check if 'otherPurpose' is set and not empty
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
        'other_purpose' => $otherReason, // Now it is always defined
    ]);
}

// Save antecedent details
function saveAntecedentInformation($database, $data)
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
        ]
    );
}


// Save antecedent details
function saveSingaporeAddress($database, $data)
{
    updateOrInsert(
        $database,
        'addresses',
        ['traveler_id' => $data['traveler_id']],
        [
            'singapore_address' => !empty($data['accommodation']) ? $data['accommodation'] : null,
            'hotel_name' => isset($data['hotelName']) ? $data['hotelName'] : null,
        ]
    );
}

// Save declarations
function saveDeclarations($database, $data)
{
    updateOrInsert($database, 'declarations', ['traveler_id' => $data['traveler_id']], [
        'agreed_to_terms' => $data['agreed_to_terms'] ?? null,
        'previous_rejections' => $data['previous_rejections'] ?? null,
        'land_packages' => $data['land_packages'] ?? null,
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
