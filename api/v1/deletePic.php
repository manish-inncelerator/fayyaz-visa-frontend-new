<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respondWithJson(['error' => 'Invalid request method'], 405);
    exit;
}

session_start();

require '../../database.php';
require '../../vendor/autoload.php';
require 'functions/validate_hu.php';

header('Content-Type: application/json');

// Retrieve headers safely across different servers
if (function_exists('getallheaders')) {
    $headers = getallheaders();
    $hu = $headers['HU'] ?? $headers['Hu'] ?? $headers['hu'] ?? null;
    $orderId = $headers['X-Order-ID'] ?? $headers['X-Order-Id'] ?? null;
    $name = $headers['X-Person-Name'] ?? null;
} else {
    // Fallback for Nginx or other servers
    $hu = $_SERVER['HTTP_HU'] ?? null;
    $orderId = $_SERVER['HTTP_X_ORDER_ID'] ?? null;
    $name = $_SERVER['HTTP_X_PERSON_NAME'] ?? null;
}

// Determine host safely
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? '';

// Validate request & HU
if (!$host || !isValidRequest($_SESSION['uuid'] ?? null, $hu, parse_url("$scheme://$host", PHP_URL_HOST)) || !isHuValid($_SESSION['uuid'] ?? null, $hu)) {
    respondWithJson(["error" => "Invalid request"], 400);
    exit;
}

// Decode JSON input
$inputData = json_decode(file_get_contents('php://input'), true);
$travelerId = isset($inputData['traveler_id']) ? intval($inputData['traveler_id']) : null;

if (!$travelerId || !$orderId || !$name) {
    respondWithJson(['error' => 'Missing parameters'], 400);
    exit;
}

// Fetch photo filename from database
$photo = $database->get("photos", "photo_filename", [
    "traveler_id" => $travelerId,
    "order_id" => $orderId
]);

if ($photo) {
    // Sanitize traveler name
    $safeName = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $name);
    $safeName = trim($safeName);
    $safeName = preg_replace('/[^a-zA-Z\s-]/', '', $safeName);
    $safeName = preg_replace('/[\s\-]+/', '-', $safeName);
    $safeName = strtolower($safeName);
    $safeName = implode('-', array_map('ucfirst', explode('-', $safeName)));

    // Construct file path safely
    $baseDir = realpath("../../user_uploads/{$orderId}/{$safeName}");

    // $photoPath = $baseDir ? "{$baseDir}/{$photo}" : null;
    $photoPath = $baseDir ? trim("{$baseDir}/{$photo}") : null;


    // Validate and delete file
    if ($photoPath && file_exists($photoPath)) {
        if (!unlink($photoPath)) {
            error_log("Failed to delete file: $photoPath");
            respondWithJson(["error" => "Failed to delete file"], 500);
            exit;
        }
    }
}

// Delete photo record from database
$delete = $database->delete("photos", [
    "traveler_id" => $travelerId,
    "order_id" => $orderId
]);

if (!$delete) {
    error_log("Database deletion error: " . json_encode($database->error));
    respondWithJson(["error" => "Failed to delete photo entry from database"], 500);
    exit;
}

respondWithJson(["success" => "Photo deleted successfully"], 200);
