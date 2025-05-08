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

if (!isHuValid($uuid, $hu)) {
    respondWithJson(["error" => "Invalid HU"], 401);
    exit;
}

// Get user ID from query parameter
$userId = isset($_GET['user_id']) ? sanitizeInput($_GET['user_id']) : null;

if (!$userId) {
    respondWithJson(["error" => "User ID is required"], 400);
    exit;
}

// Fetch notifications from database
$notifications = $database->select("notifications", [
    "id",
    "notification_message",
    "is_seen",
    "created_at"
], [
    "customer_id" => $userId,
    "ORDER" => ["created_at" => "DESC"],
    "LIMIT" => 50
]);

if ($notifications === false) {
    respondWithJson(["error" => "Failed to fetch notifications"], 500);
    exit;
}

respondWithJson([
    "success" => true,
    "notifications" => $notifications
], 200);
