<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../../database.php';
require 'functions/validate_hu.php';

header('Content-Type: application/json');

// Fetch input data
$inputData = json_decode(file_get_contents("php://input"), true);

// Retrieve headers safely (Nginx-compatible)
if (function_exists('getallheaders')) {
    $headers = getallheaders();
} else {
    $headers = [
        'HU' => $_SERVER['HTTP_HU'] ?? null
    ];
}

$uuid = $_SESSION['uuid'] ?? null;
$hu = $headers['HU'] ?? $headers['Hu'] ?? $headers['hu'] ?? null;

// Securely determine host
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? '';

// Validate request & HU
if (!$host || !isValidRequest($uuid, $hu, parse_url("$scheme://$host", PHP_URL_HOST))) {
    respondWithJson(["error" => "Invalid request"], 400);
    exit;
}

$email = filter_var($inputData['email'] ?? '', FILTER_SANITIZE_EMAIL);
$password = trim($inputData['password'] ?? '');

if (!$email || !$password) {
    respondWithJson(["error" => "Missing required fields"], 400);
    exit;
}

// Fetch user details from the database
$user = $database->get("users", [
    "user_id",
    "email",
    "password",
    "is_email_verified",
    "is_ban",
    "is_deleted"
], ["email" => $email]);

if (!$user || !password_verify($password, $user['password'])) {
    respondWithJson(["error" => "Invalid credentials"], 401);
    exit;
}
// check if user is_email_verified
if ((int)$user['is_email_verified'] === 0) {
    respondWithJson(["error" => "Your email is not verified. Please verify your email to continue."], 403);
    exit;
}

// Check if the user is banned or deleted
if ((int)$user['is_ban'] === 1) {
    respondWithJson(["error" => "Your account has been banned. Please contact support."], 403);
    exit;
}

if ((int)$user['is_deleted'] === 1) {
    respondWithJson(["error" => "This account has been deleted. Contact support if this is an error."], 403);
    exit;
}

// Set session variables for authenticated user
$_SESSION['user_id'] = $user['user_id'];
$_SESSION['email'] = $user['email'];


respondWithJson(["success" => "Login successful"], 200);
