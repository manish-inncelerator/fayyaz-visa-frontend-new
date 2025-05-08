<?php
session_start();

require '../../database.php';

// Ensure the request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Invalid Request Method"]);
    exit;
}

// Get headers
$headers = array_change_key_case(getallheaders(), CASE_LOWER);
if (!isset($headers['auth'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing Authentication Header"]);
    exit;
}

$secureHash = $headers['auth'];

// Validate session authKey against secureHash
if (!isset($_SESSION['authKey'])) {
    http_response_code(401);
    echo json_encode(["error" => "Session Expired or Invalid"]);
    exit;
}

if (md5($_SESSION['authKey']) !== $secureHash) {
    http_response_code(403);
    echo json_encode(["error" => "Invalid Request"]);
    exit;
}

// Decode JSON payload
$input = json_decode(file_get_contents('php://input'), true);

// Validate input
$name = isset($input['userName']) ? trim(stripslashes(strip_tags($input['userName']))) : null;
$email = isset($input['userEmail']) ? trim(stripslashes(strip_tags($input['userEmail']))) : null;
$phone = isset($input['userMobile']) ? trim(stripslashes(strip_tags($input['userMobile']))) : null;
$country = isset($input['requestedCountry']) ? trim(stripslashes(strip_tags($input['requestedCountry']))) : null;

if (!$name || !$email || !$phone || !$country) {
    http_response_code(422);
    echo json_encode(["error" => "All fields are required."]);
    exit;
}

// Insert into DB
$req = $database->insert("visa_country_requests", [
    "name" => $name,
    "email" => $email,
    "phone" => $phone,
    "country" => $country
]);

if ($req) {
    http_response_code(200);
    echo json_encode(["success" => "Request submitted successfully!"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to submit request"]);
}
