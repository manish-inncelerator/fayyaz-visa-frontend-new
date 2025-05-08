<?php

// Ensure the session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../../database.php';
require '../../sendMail.php';
require 'functions/validate_hu.php';

header('Content-Type: application/json');
$inputData = json_decode(file_get_contents("php://input"), true);

// Retrieve session data
$uuid = $_SESSION['uuid'] ?? null;
$userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null; // Ensure user_id is an integer

// Retrieve headers safely across different servers
if (function_exists('getallheaders')) {
    $headers = getallheaders();
    $hu = $headers['HU'] ?? $headers['Hu'] ?? $headers['hu'] ?? null;
} else {
    // Fallback for Nginx or other servers
    $hu = $_SERVER['HTTP_HU'] ?? null;
}

// Get the host safely
$host = $_SERVER['HTTP_HOST'] ?? '';

// Validate request & HU
if (!isValidRequest($uuid, $hu, $host) || !isHuValid($uuid, $hu)) {
    respondWithJson(["error" => "Invalid request"], 400);
    exit;
}

// Sanitize input
$currentPassword = sanitizeInput($inputData['password'] ?? '');

// Validate input fields
if (!$currentPassword) {
    respondWithJson(["error" => "All fields are required"], 400);
    exit;
}

// Fetch user data
$user = $database->get("users", ["password", "is_deleted"], ["user_id" => $userId]);

// Check if user exists and password is correct
if (!$user || !password_verify($currentPassword, $user['password'])) {
    respondWithJson(["error" => "Invalid password"], 401);
    exit;
}

// Prevent re-deleting an already deleted account
if ($user['is_deleted'] == 1) {
    respondWithJson(["error" => "Account already deleted"], 400);
    exit;
}

// Disable user account
$update = $database->update("users", ["is_deleted" => 1], ["user_id" => $userId]);

sendEmail($emailAddress, 'Account Delete Confirmation', 'default', 'Your accound has been successfully deleted.', false);

// Check if update was successful
if ($database->error[0] !== "00000") {
    error_log("Account deletion failed for user ID: $userId. Error: " . json_encode($database->error));
    respondWithJson(["error" => "Failed to delete account"], 500);
    exit;
}

respondWithJson(["success" => "Account deleted"], 200);
