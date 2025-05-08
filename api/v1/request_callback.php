<?php
require_once '../../slack_send_msg.php';
require_once '../../sendMail.php';

// Read raw POST data
$requestPayload = file_get_contents("php://input");

// Decode JSON into an associative array
$data = json_decode($requestPayload, true);

// Extract and sanitize values
$mobileNumber = isset($data['mobileNumber']) ? filter_var(trim($data['mobileNumber']), FILTER_SANITIZE_FULL_SPECIAL_CHARS) : 'Unknown';
$countryCode = isset($data['countryCode']) ? filter_var(trim($data['countryCode']), FILTER_SANITIZE_FULL_SPECIAL_CHARS) : 'Unknown';

// Send a channel message
$slackBot->sendChannelMessage('C08J18RV9KN', "New Callback Request: $countryCode $mobileNumber");

// Send an email and check if it was successful
$emailSent = sendEmail('bhushan.inncelerator@gmail.com', 'New Callback Request', 'default', "New callback request received for: $countryCode $mobileNumber");

$response = [
    'status' => $emailSent ? 'success' : 'error',
    'message' => $emailSent ? 'Callback request processed' : 'Failed to send email',
];

header('Content-Type: application/json');
echo json_encode($response);
