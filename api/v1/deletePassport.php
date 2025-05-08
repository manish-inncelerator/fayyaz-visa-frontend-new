<?php
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

// Fetch passport filename
$passport = $database->get("passports", "passport_filename", [
    "traveler_id" => $travelerId,
    "order_id" => $orderId
]);

if ($passport) {
    // Sanitize traveler name
    $safeName = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $name);
    $safeName = trim($safeName);
    $safeName = preg_replace('/[^a-zA-Z\s-]/', '', $safeName);
    $safeName = preg_replace('/[\s\-]+/', '-', $safeName);
    $safeName = strtolower($safeName);
    $safeName = implode('-', array_map('ucfirst', explode('-', $safeName)));

    // Construct file path safely
    $baseDir = realpath("../../user_uploads/{$orderId}/{$safeName}/passport");
    // $passportPath = $baseDir ? "{$baseDir}/{$passport}" : null;
    $passportPath = $baseDir ? trim("{$baseDir}/{$passport}") : null;


    // Validate and delete file
    if ($passportPath && file_exists($passportPath)) {
        if (!unlink($passportPath)) {
            error_log("Failed to delete file: $passportPath");
            respondWithJson(["error" => "Failed to delete file"], 500);
            exit;
        }
    }
}

// Delete passport record from database
$delete = $database->delete("passports", ["traveler_id" => $travelerId, "order_id" => $orderId]);

if (!$delete) {
    error_log("Database deletion error: " . json_encode($database->error));
    respondWithJson(["error" => "Failed to delete photo entry from database"], 500);
    exit;
}

respondWithJson(["success" => "Passport deleted successfully"], 200);
