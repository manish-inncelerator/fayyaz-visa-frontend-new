<?php

session_start();

require '../../database.php';
require 'functions/validate_hu.php';
require '../../sendMail.php'; // Include the sendMail function

header('Content-Type: application/json');
$inputData = json_decode(file_get_contents("php://input"), true);

// print_r($inputData);

// Retrieve session data
$uuid = $_SESSION['uuid'] ?? null;
$userId = $_SESSION['user_id'] ?? null;

// Handle headers compatibility across different servers
// Retrieve headers safely across different servers
if (function_exists('getallheaders')) {
    $headers = getallheaders();
    $hu = $headers['HU'] ?? $headers['Hu'] ?? $headers['hu'] ?? null;
} else {
    // For Nginx or other servers, use $_SERVER
    $hu = $_SERVER['HTTP_HU'] ?? null;
}

// Validate request & HU
if (!isValidRequest($uuid, $hu, $_SERVER['HTTP_HOST']) || !isHuValid($uuid, $hu)) {
    respondWithJson(["error" => "Invalid request"], 400);
    exit;
}

// Sanitize input
$newPassword = sanitizeInput($inputData['newPassword'] ?? '');
$confirmNewPassword = sanitizeInput($inputData['confirmPassword'] ?? '');
$resetCode = sanitizeInput($inputData['resetCode'] ?? '');
$emailAddress = filter_var($inputData['email'] ?? '', FILTER_SANITIZE_EMAIL);

// Check if user exists
$user = $database->get("users", "email", ["email" => $emailAddress]);
if (!$user) {
    respondWithJson(["error" => "User not found."], 401);
    exit;
}

$storedResetCode = $database->get("reset_password", "reset_code", ["email" => $emailAddress]);

// Debugging: Check what the database is returning
if ($storedResetCode === false || $storedResetCode === null) {
    respondWithJson(["error" => "Reset code not found or email is incorrect"], 401);
    exit;
}

// Check if reset code is correct
if ($storedResetCode !== $resetCode) {
    respondWithJson(["error" => "Invalid reset code"], 401);
    exit;
}

// Validate input fields
if (!$newPassword || !$emailAddress || !$resetCode) {
    respondWithJson(["error" => "All fields are required"], 400);
    exit;
}

// Validate password complexity
if (strlen($newPassword) < 8 || !preg_match('/[A-Z]/', $newPassword) || !preg_match('/[0-9]/', $newPassword)) {
    respondWithJson(["error" => "Password must be at least 8 characters long and contain at least one uppercase letter and one number."], 400);
    exit;
}

if ($newPassword !== $confirmNewPassword) {
    respondWithJson(["error" => "New passwords do not match"], 400);
    exit;
}

// Get the last reset request time
$lastRequestTime = $database->get("reset_password", "reset_code_request_on", [
    "email" => $emailAddress
]);

if ($lastRequestTime) {
    // Convert last request time to a timestamp
    $lastRequestTimestamp = strtotime($lastRequestTime);
    $timeLimit = strtotime("-5 hours");

    if ($lastRequestTimestamp <= $timeLimit) {
        respondWithJson(["error" => "Your last reset request was more than 5 hours ago. Please request a new reset link."], 401);
        exit;
    }
}

// Hash Password
$options = [
    'memory_cost' => 65536,
    'time_cost'   => 4,
    'threads'     => 2,
];
$hashedPassword = password_hash($newPassword, PASSWORD_ARGON2ID, $options);

// Update the user's password
$update = $database->update("users", ["password" => $hashedPassword], ["email" => $emailAddress]);

// Check if update was successful
if ($database->error) {
    error_log("Password update failed for user: $emailAddress. Error: " . json_encode($database->error));
    respondWithJson(["error" => "Failed to update password"], 500);
    exit;
}

// Send verification email after password update
$verificationResult = sendEmail($emailAddress, 'Password Change Confirmation', 'default', 'Your password has been successfully changed.', false);
if (!$verificationResult['success']) {
    error_log("Failed to send verification email to: $emailAddress. Error: " . $verificationResult['message']);
}

// Delete reset request securely
$database->delete("reset_password", ["email" => (string)$emailAddress]);

respondWithJson(["success" => "Password updated successfully"], 200);
