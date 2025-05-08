<?php
header('Content-Type: application/json');
require_once '../../database.php';
require 'functions/validate_hu.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validate Request & Headers
$uuid = $_SESSION['uuid'] ?? null;
$headers = getallheaders();
$hu = $headers['HU'] ?? $headers['Hu'] ?? $headers['hu'] ?? null;
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = parse_url($scheme . '://' . ($_SERVER['HTTP_HOST'] ?? ''), PHP_URL_HOST);

if (!isValidRequest($uuid, $hu, $host) || !isHuValid($uuid, $hu)) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request'
    ]);
    exit;
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}

// Get JSON data from request body
$data = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (!isset($data['notification_id']) || !is_numeric($data['notification_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid notification ID'
    ]);
    exit;
}

try {
    // Delete notification
    $result = $database->delete(
        'notifications',
        ['id' => $data['notification_id']]
    );

    if ($result->rowCount() > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'Notification deleted successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Notification not found'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error deleting notification: ' . $e->getMessage()
    ]);
}
