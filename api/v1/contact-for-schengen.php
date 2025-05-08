<?php

session_start();

require '../../database.php';
require 'functions/validate_hu.php';
require '../../sendMail.php';

header('Content-Type: application/json');
$inputData = json_decode(file_get_contents("php://input"), true);

// Retrieve session data
$uuid = $_SESSION['uuid'] ?? null;
$userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;

// Retrieve headers safely across different servers
if (function_exists('getallheaders')) {
    $headers = getallheaders();
    $hu = $headers['HU'] ?? $headers['Hu'] ?? $headers['hu'] ?? null;
} else {
    // Fallback for Nginx or other servers
    $hu = $_SERVER['HTTP_HU'] ?? null;
}

// Validate request & HU
if (!isValidRequest($uuid, $hu, $_SERVER['HTTP_HOST']) || !isHuValid($uuid, $hu)) {
    respondWithJson(["error" => "Invalid request"], 400);
    exit;
}

// Sanitize inputs
$name = sanitizeInput($inputData['name'] ?? '');
$email = sanitizeInput($inputData['email'] ?? '');
$phone = sanitizeInput($inputData['phone'] ?? '');
$message = sanitizeInput($inputData['message'] ?? '');
$userCountryCode = sanitizeInput($inputData['userCountryCode'] ?? '');
$countryName = sanitizeInput($inputData['country_name'] ?? '');

// Validate input fields
if (!$name || !$email || !$phone || !$message) {
    respondWithJson(["error" => "All fields are required"], 400);
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respondWithJson(["error" => "Invalid email format"], 400);
    exit;
}

// Validate phone number (basic validation)
if (!preg_match('/^[0-9+\-\s()]{8,20}$/', $phone)) {
    respondWithJson(["error" => "Invalid phone number format"], 400);
    exit;
}

// Join country code and phone number
$fullPhoneNumber = $userCountryCode . $phone;

try {
    // Insert contact form submission into database using Medoo
    $database->insert("contact_submissions", [
        "name" => $name,
        "email" => $email,
        "phone" => $fullPhoneNumber, // Use the combined phone number
        "message" => $message,
        "user_id" => $userId,
        "country_name" => $countryName,
        "created_at" => date('Y-m-d H:i:s'),
        "status" => "pending"
    ]);

    // write email body
    $emailBody = "Hi Admin,<br>$name has contacted you for Schengen Visa to $countryName. Please find the details below:<br><hr>";
    $emailBody .= "<table>";
    $emailBody .= "<tr><td><strong>Name:</strong></td><td>" . $name . "</td></tr>";
    $emailBody .= "<tr><td><strong>Email:</strong></td><td>" . $email . "</td></tr>";
    $emailBody .= "<tr><td><strong>Phone:</strong></td><td>" . $fullPhoneNumber . "</td></tr>";
    $emailBody .= "<tr><td><strong>Message:</strong></td><td>" . $message . "</td></tr>";
    $emailBody .= "<tr><td><strong>Country Name:</strong></td><td>" . $countryName . "</td></tr>";
    $emailBody .= "</table>";

    // Send email notification to admin (you can implement this later)
    sendEmail('visa@fayyaztravels.com', 'New Contact Form Submission for Schengen Visa', 'default', $emailBody);

    respondWithJson([
        "success" => "Thank you for contacting us. We will get back to you within 24 hours. Please expect a call from us.",
        "submission_id" => $database->id()
    ], 200);
} catch (Exception $e) {
    error_log("Contact form submission error: " . $e->getMessage());
    respondWithJson(["error" => "An error occurred while processing your request"], 500);
    exit;
}
