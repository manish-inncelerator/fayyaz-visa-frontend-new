<?php
require('../../database.php');


require_once '../../vendor/autoload.php';

use Hashids\Hashids;

require 'functions/validate_hu.php';

// Get JSON input
$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

// Get the order_id from the X-Order-Id header
$order_id = isset($_SERVER['HTTP_X_ORDER_ID']) ? $_SERVER['HTTP_X_ORDER_ID'] : null;
$person_name = isset($_SERVER['HTTP_X_PERSON_NAME']) ? $_SERVER['HTTP_X_PERSON_NAME'] : null;

if (!$order_id) {
    http_response_code(400);
    echo json_encode(['message' => 'Missing X-Order-Id header']);
    exit;
}

// Sanitize input data
$data = sanitizeData($data);


// $uuid = $_SESSION['uuid'] ?? null;

// // Fetch HU from headers
// $headers = getallheaders();
// $hu = $headers['HU'] ?? $headers['Hu'] ?? $headers['hu'] ?? null;



// $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
// $host = parse_url($scheme . '://' . ($_SERVER['HTTP_HOST'] ?? ''), PHP_URL_HOST);

// if (defined('DEV_MODE') && DEV_MODE) {
//     echo json_encode(["debug" => ["uuid" => $uuid, "hu" => $hu, "host" => $host]]);
// }

// if (!isValidRequest($uuid, $hu, $host)) {
//     respondWithJson(["error" => "Invalid request", "debug" => getErrorDetails($uuid, $hu, $host)], 400);
// }

// if (!isHuValid($uuid, $hu)) {
//     respondWithJson(["error" => "Invalid HU"], 401);
// }



try {
    // Handle all data processing in one go
    handlePersonalInformation($database, $data, $order_id);
    handleTravelDocument($database, $data, $order_id);
    handleAddressInformation($database, $data, $order_id);
    handleSingaporeAddress($database, $data, $order_id);
    handleOccupationEducation($database, $data, $order_id);
    handleVisitInformation($database, $data, $order_id);
    handleAdditionalInformation($database, $data, $order_id);
    handleAntecedentInformation($database, $data, $order_id);
    handleDeclarations($database, $data, $order_id);
    // Fetch traveler data from DB
    $users = $database->select("travelers", ["name", "id", "order_id"], [
        "order_id" => $order_id,
        "name" => $data['fullName'],
    ]);

    // Check if any user has incomplete details and process the first one
    foreach ($users as $user) {
        if ($user['id'] == $data['traveler_id'] && $user['order_id'] == $order_id) {
            // Mark this user as having full details
            $database->update("details_checklist", ["is_finished" => 1], [
                "order_id" => $order_id,
                "traveler_id" => $data['traveler_id']
            ]);

            // Get the next traveler who has incomplete details
            $nextUser = $database->get("details_checklist", ["traveler_id"], [
                "order_id" => $order_id,
                "is_finished" => 0 // Ensure this column exists
            ]);


            if ($nextUser) {
                // Redirect to the next traveler's details page
                // header('Location: ../../application/' . $order_id . '/details?tid=' . urlencode($nextTraveler['id']));
                // exit;

                // Step 1: Create a Hashids object with a salt and min length
                $hashids = new Hashids('3Y1n?BuiI$5b(F{)I&J|8mXb', 16); // '6' = minimum length of encoded ID
                $encryptedIdNew = $hashids->encode($nextUser['traveler_id']);

                http_response_code(200);
                echo json_encode([
                    'status' => 200,
                    'message' => 'next_traveler',
                    'tid' => 'tid=' . $encryptedIdNew
                ]);
            } else {
                // No incomplete travelers found, return a response
                http_response_code(200);
                echo json_encode(['status' => 200, 'message' => 'make_payment']);
                exit;
            }
        } else {

            // No incomplete travelers found, return a response
            http_response_code(200);
            echo json_encode(['status' => 200, 'message' => 'make_payment']);
            exit;
        }
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => 'An error occurred', 'error' => $e->getMessage()]);
}

// Function to sanitize the input data
function sanitizeData($data)
{
    // Sanitize strings
    foreach ($data as $key => $value) {
        if (is_string($value)) {
            $data[$key] = trim($value); // Trim whitespace
            $data[$key] = stripslashes($data[$key]); // Remove slashes
            $data[$key] = htmlspecialchars($data[$key], ENT_QUOTES, 'UTF-8'); // Convert special characters to HTML entities
        } elseif (is_array($value)) {
            // Recursively sanitize array data
            $data[$key] = sanitizeData($value);
        } elseif (is_null($value)) {
            // Skip htmlspecialchars if the value is null
            $data[$key] = null; // Ensure null remains null
        }
    }

    // Optionally validate specific fields (e.g., email, phone number, etc.)
    if (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format");
    }
    if (isset($data['phoneNumber']) && !preg_match('/^[0-9]+$/', $data['phoneNumber'])) {
        throw new Exception("Invalid phone number format");
    }

    return $data;
}

// General function to check and insert or update data
function checkAndSaveData($database, $table, $where, $data)
{
    // Add order_id to the data
    $data['order_id'] = $where['order_id'];

    // Check if data exists
    $existingRecord = $database->get($table, '*', $where);

    if ($existingRecord) {
        // Check for the latest data, assuming there's a timestamp column (like 'created_at')
        if (isset($existingRecord['created_at']) && strtotime($existingRecord['created_at']) < strtotime($data['created_at'])) {
            // Update the existing record if it is not the latest
            $database->update($table, $data, $where);
        }
    } else {
        // Insert new record if no existing data
        $database->insert($table, array_merge($where, $data));
    }
}

// Handle saving personal information
function handlePersonalInformation($database, $data, $order_id)
{
    $personalData = [
        'full_name' => $data['fullName'],
        'date_of_birth' => $data['dob'],
        'gender' => $data['gender'],
        'race' => $data['raceSelect'],
        'country_of_birth' => $data['countrySelect'],
        'nationality' => $data['nationalitySelect'],
        'religion' => $data['religionSelect'],
        'marital_status' => $data['maritalStatus'],
        'spouse_nationality' => $data['spouseNationality'],
        'created_at' => date('Y-m-d H:i:s'), // Update timestamp
    ];
    checkAndSaveData($database, 'applicants', ['traveler_id' => $data['traveler_id'], 'order_id' => $order_id], $personalData);
}

// Handle saving travel document details
function handleTravelDocument($database, $data, $order_id)
{
    $travelDocumentData = [
        'passport_type' => $data['passportType'],
        'country_of_issue' => $data['countryOfIssue'],
        'place_of_issue' => $data['placeOfIssue'],
        'passport_number' => $data['passportNumber'],
        'issue_date' => $data['issueDate'],
        'expiry_date' => $data['expiryDate'],
        'created_at' => date('Y-m-d H:i:s'),
    ];
    checkAndSaveData($database, 'travel_documents', ['traveler_id' => $data['traveler_id'], 'order_id' => $order_id], $travelDocumentData);
}

// Handle saving address information
function handleAddressInformation($database, $data, $order_id)
{
    $addressData = [
        'country_of_origin' => $data['countryOfOrigin'],
        'permanent_address' => $data['permanentAddress'],
        'created_at' => date('Y-m-d H:i:s'),
    ];
    checkAndSaveData($database, 'addresses', ['traveler_id' => $data['traveler_id'], 'order_id' => $order_id], $addressData);
}

// Handle saving address information for Singapore
function handleSingaporeAddress($database, $data, $order_id)
{
    $addressData2 = [
        'singapore_address' => $data['accommodation'],
        'hotel_name' => $data['hotelName'] ?? null,
        'created_at' => date('Y-m-d H:i:s'),
    ];
    checkAndSaveData($database, 'addresses', ['traveler_id' => $data['traveler_id'], 'order_id' => $order_id], $addressData2);
}

// Handle saving occupation and education details
function handleOccupationEducation($database, $data, $order_id)
{
    $contactNumber = $data['countryCode'] . $data['phoneNumber'];
    $occupationEducationData = [
        'occupation' => $data['occupationSelect'],
        'annual_income' => $data['annualIncome'],
        'email' => $data['email'],
        'contact_number' => $contactNumber,
        'highest_qualification' => $data['highestQualification'],
        'created_at' => date('Y-m-d H:i:s'),
    ];
    checkAndSaveData($database, 'occupation_education', ['traveler_id' => $data['traveler_id'], 'order_id' => $order_id], $occupationEducationData);
}

// Handle saving visit information
function handleVisitInformation($database, $data, $order_id)
{
    $otherReason = !empty($data['otherPurpose']) && $data['purposeOfVisit'] === 'other' ? $data['otherPurpose'] : null;
    $visitInformationData = [
        'arrival_date' => $data['arrivalDate'],
        'inflight_number' => $data['inflightNumber'],
        'visa_type' => $data['visaType'],
        'stay_duration' => $data['stayDuration'],
        'departure_date' => $data['departureDate'],
        'outflight_number' => $data['outflightNumber'],
        'purpose_of_visit' => $data['purposeOfVisit'],
        'other_purpose' => $otherReason,
        'created_at' => date('Y-m-d H:i:s'),
    ];
    checkAndSaveData($database, 'visit_information', ['traveler_id' => $data['traveler_id'], 'order_id' => $order_id], $visitInformationData);
}

// Handle saving additional information
function handleAdditionalInformation($database, $data, $order_id)
{
    // If 'travelling_alone' changes, clear 'travel_companions' when necessary
    if ($data['travellingAlone'] === 'yes') {
        $data['travelCompanions'] = null;
    }
    $additionalInformationData = [
        'travelling_alone' => $data['travellingAlone'],
        'travel_companions' => $data['travelCompanions'] ?? null,
        'created_at' => date('Y-m-d H:i:s'),
    ];
    checkAndSaveData($database, 'additional_information', ['traveler_id' => $data['traveler_id'], 'order_id' => $order_id], $additionalInformationData);
}

// Handle saving antecedent information
function handleAntecedentInformation($database, $data, $order_id)
{
    $antecedentData = [
        'resided_abroad' => $data['residedAbroad'] ?? null,
        'refused_entry' => $data['refusedEntry'] ?? null,
        'convicted' => $data['convicted'] ?? null,
        'prohibited' => $data['prohibitedEntry'] ?? null,
        'different_passport' => $data['differentPassport'] ?? null,
        'antecedent_details' => $data['additionalDetails'] ?? null,
        'travelling_from_country' => $data['travellingFrom'] ?? null,
        'created_at' => date('Y-m-d H:i:s'),
    ];
    checkAndSaveData($database, 'antecedent_information', ['traveler_id' => $data['traveler_id'], 'order_id' => $order_id], $antecedentData);
}

// Handle saving declarations
function handleDeclarations($database, $data, $order_id)
{
    $declaration_agreed = $data['declaration_agreed'] ?? '';
    // Ensure boolean checkboxes have a default value if not provided
    $responsibility_for_errors = isset($data['responsibility_for_errors']) ? 'yes' : 'no';
    $untrue_information_penalty = isset($data['untrue_information_penalty']) ? 'yes' : 'no';
    $submission_verification = isset($data['submission_verification']) ? 'yes' : 'no';
    $fraudulent_application_suspension = isset($data['fraudulent_application_suspension']) ? 'yes' : 'no';
    $deposit_withdrawal = isset($data['deposit_withdrawal']) ? 'yes' : 'no';
    $information_verification = isset($data['information_verification']) ? 'yes' : 'no';
    $applicant_responsibility = isset($data['applicant_responsibility']) ? 'yes' : 'no';
    $land_packages_interest = isset($data['land_packages_interest']) ? 'yes' : 'no';

    $declarationsData = [
        'traveler_id' => $data['traveler_id'] ?? null,
        'declaration_agreed' => $declaration_agreed,
        'responsibility_for_errors' => $responsibility_for_errors,
        'untrue_information_penalty' => $untrue_information_penalty,
        'submission_verification' => $submission_verification,
        'fraudulent_application_suspension' => $fraudulent_application_suspension,
        'deposit_withdrawal' => $deposit_withdrawal,
        'information_verification' => $information_verification,
        'applicant_responsibility' => $applicant_responsibility,
        'land_packages_interest' => $land_packages_interest,
        'is_finished' => 1,
        'order_id' => $order_id,
        'created_at' => date('Y-m-d H:i:s')
    ];

    // Use checkAndSaveData for saving the data
    checkAndSaveData($database, 'declaration_terms', ['order_id' => $order_id], $declarationsData);
}
