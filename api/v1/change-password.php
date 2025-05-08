<?php

session_start();

require '../../database.php';
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

// Validate request & HU
if (!isValidRequest($uuid, $hu, $_SERVER['HTTP_HOST']) || !isHuValid($uuid, $hu)) {
    respondWithJson(["error" => "Invalid request"], 400);
    exit;
}

// Sanitize inputs
$currentPassword = sanitizeInput($inputData['currentPassword'] ?? '');
$newPassword = sanitizeInput($inputData['newPassword'] ?? '');
$confirmNewPassword = sanitizeInput($inputData['confirmNewPassword'] ?? '');

// Validate input fields
if (!$currentPassword || !$newPassword || !$confirmNewPassword) {
    respondWithJson(["error" => "All fields are required"], 400);
    exit;
}

if ($newPassword !== $confirmNewPassword) {
    respondWithJson(["error" => "New passwords do not match"], 400);
    exit;
}

// Validate password complexity
if (strlen($newPassword) < 8 || !preg_match('/[A-Z]/', $newPassword) || !preg_match('/[0-9]/', $newPassword)) {
    respondWithJson(["error" => "Password must be at least 8 characters long and contain at least one uppercase letter and one number."], 400);
    exit;
}

// Fetch user password from the database
$user = $database->get("users", ["password"], ["user_id" => $userId]);

if (!$user || !password_verify($currentPassword, $user['password'])) {
    respondWithJson(["error" => "Invalid current password"], 401);
    exit;
}

// Prevent updating with the same password
if (password_verify($newPassword, $user['password'])) {
    respondWithJson(["error" => "New password must be different from the current password."], 400);
    exit;
}

// Hash the new password

$options = [
    'memory_cost' => 65536,
    'time_cost'   => 4,
    'threads'     => 2,
];
$hashedPassword = password_hash($newPassword, PASSWORD_ARGON2ID, $options);

// Update the user's password
$update = $database->update("users", ["password" => $hashedPassword], ["user_id" => $userId]);

// Check if update was successful
if ($database->error[0] !== "00000") {
    error_log("Password update failed for user ID: $userId. Error: " . json_encode($database->error));
    respondWithJson(["error" => "Failed to update password"], 500);
    exit;
}

respondWithJson(["success" => "Password updated successfully"], 200);
