<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respondWithJson(['error' => 'Invalid request method'], 405);
    exit;
}

// Start session only if not already started
if (!isset($_SESSION)) {
    session_start();
}

require '../../database.php';
require '../../vendor/autoload.php';
require 'functions/validate_hu.php';
require '../../sendMail.php'; // Include email functionality

header('Content-Type: application/json'); // Ensure JSON response format

// Fetch headers & session data
$headers = function_exists('getallheaders') ? getallheaders() : [];
$uuid = $_SESSION['uuid'] ?? null;
$hu = $headers['HU'] ?? $headers['Hu'] ?? $headers['hu'] ?? null;
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? '';

// Validate request & HU
if (
    !function_exists('isValidRequest') || !function_exists('isHuValid') ||
    !isValidRequest($uuid, $hu, $host) || !isHuValid($uuid, $hu)
) {
    respondWithJson(["error" => "Invalid request"], 400);
    exit;
}

// Fetch input data
$inputData = json_decode(file_get_contents('php://input'), true);
$email = filter_var($inputData['email'] ?? '', FILTER_VALIDATE_EMAIL);

if (!$email) {
    respondWithJson(["error" => "Invalid or missing email."], 400);
    exit;
}

// Check if the email exists in the users table
$userExists = $database->has("users", ["email" => $email]);

if (!$userExists) {
    respondWithJson(["error" => "User not found."], 404);
    exit;
}

// Check if the email is verified
$isVerified = $database->get("users", "is_email_verified", ["email" => $email]);

if (!$isVerified) {
    respondWithJson(["error" => "Email not verified."], 403);
    exit;
}

// Get current time and 5-hour limit for reset requests
$current_time = date('Y-m-d H:i:s');
$time_limit = date('Y-m-d H:i:s', strtotime('-5 hours'));

// Check if a reset request exists for this email within the last 5 hours
$existingRequest = $database->get("reset_password", "reset_code_request_on", [
    "email" => $email,
    "reset_code_request_on[>]" => $time_limit
]);

if ($existingRequest) {
    respondWithJson(["error" => "A reset request already exists. Please try again later."], 400);
    exit;
}

// Generate a new reset code
$reset_code = bin2hex(random_bytes(16));

// Insert new reset request
$insert_status = $database->insert("reset_password", [
    "email" => $email,
    "reset_code" => $reset_code,
    "reset_code_request_on" => $current_time,
    "reset_code_expiration_time" => date('Y-m-d H:i:s', strtotime('+5 hours'))
]);

if ($insert_status) {
    // Create reset password link
    $resetLink = "$scheme://$host/visa/auth/forgot-password?reset=1&resetCode=$reset_code&email=$email";

    // Send email with reset link
    $emailBody = "<h2>Password Reset Request</h2>
                 <p>You recently requested to reset your password. Click the button below to reset it:</p>";

    $emailResult = sendEmail(
        $email,
        "Password Reset Request",
        "default",
        $emailBody,
        true,
        $resetLink
    );

    // add a response for email result
    // print_r($emailResult);
    if ($emailResult['success']) {
        respondWithJson([
            "success" => "Your password reset request has been created and sent to your email. Please check your spam folder if you don't see it in your inbox.",
            "status" => 200
        ], 200);
    } else {
        respondWithJson(["error" => "Failed to send email."], 500);
    }
} else {
    respondWithJson(["error" => "Failed to create reset request."], 500);
}
