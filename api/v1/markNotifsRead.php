<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../database.php';
require_once 'functions/validate_hu.php';

header('Content-Type: application/json');

// Validate Request & Headers
$uuid = $_SESSION['uuid'] ?? null;
$headers = getallheaders();
$hu = $headers['HU'] ?? $headers['Hu'] ?? $headers['hu'] ?? null;
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = parse_url($scheme . '://' . ($_SERVER['HTTP_HOST'] ?? ''), PHP_URL_HOST);

if (!isValidRequest($uuid, $hu, $host) || !isHuValid($uuid, $hu)) {
    respondWithJson(["error" => "Invalid request"], 400);
    exit;
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respondWithJson(["error" => "Invalid request method"], 400);
    exit;
}

// Get user ID from query parameter
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

// Validate user ID
if ($user_id <= 0) {
    respondWithJson(["error" => "Invalid user ID"], 400);
    exit;
}

try {
    // Update all notifications for the user to mark them as read
    $result = $database->update(
        'notifications',
        ['is_seen' => 1],
        [
            'customer_id' => $user_id,
            'is_seen' => 0
        ]
    );

    // Check if update was successful
    if ($result) {
        respondWithJson(["success" => "All notifications marked as read"], 200);
    } else {
        respondWithJson(["error" => "Failed to mark notifications as read"], 500);
    }
} catch (Exception $e) {
    respondWithJson(["error" => "Error: " . $e->getMessage()], 500);
}
