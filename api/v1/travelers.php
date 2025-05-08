<?php
session_start();
require '../../database.php';
require 'functions/validate_hu.php';

header('Content-Type: application/json');
$inputData = json_decode(file_get_contents("php://input"), true);

$uuid = $_SESSION['uuid'] ?? null;
$headers = array_change_key_case(getallheaders(), CASE_LOWER);
$hu = $headers['hu'] ?? null;

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = parse_url($scheme . '://' . ($_SERVER['HTTP_HOST'] ?? ''), PHP_URL_HOST);

// Validate request and HU header
if (!isValidRequest($uuid, $hu, $host)) {
    respondWithJson(["error" => "Invalid request", "debug" => getErrorDetails($uuid, $hu, $host)], 400);
}

if (!isHuValid($uuid, $hu)) {
    respondWithJson(["error" => "Invalid HU"], 401);
}

$errors = [];
$travelersData = [];
$insertSuccess = false;

$order_id = isset($inputData['order_id']) ? sanitizeInput($inputData['order_id']) : 0;
$action = $inputData['action'] ?? 'insert';
$is_finished = 1;

// Ensure directories exist safely
$baseDir = realpath('../../user_uploads') ?: '../../user_uploads';
$orderDir = "$baseDir/$order_id";

if (!is_dir($baseDir)) mkdir($baseDir, 0777, true);
if (!is_dir($orderDir)) mkdir($orderDir, 0777, true);

foreach ($inputData as $key => $value) {
    if (preg_match('/^name_(\d+)$/', $key, $matches)) {
        $index = $matches[1];

        $travelersData[] = [
            'order_id' => $order_id,
            'name' => sanitizeInput($inputData["name_$index"]),
            'passport' => sanitizeInput($inputData["passport_$index"]),
            'dob' => sanitizeInput($inputData["dob_$index"]),
            'nationality' => sanitizeInput($inputData["nationality_$index"]),
            'passport_country' => sanitizeInput($inputData["passport_country_$index"] ?? ''),
        ];

        // Create sanitized directory name with hyphens instead of spaces
        $sanitizedName = preg_replace('/[^a-zA-Z-]/', '', str_replace(' ', '-', $inputData["name_$index"]));
        $travelerDir = "$orderDir/$sanitizedName";

        if ($action === 'update') {
            $existingTraveler = $database->get('travelers', '*', [
                'order_id' => $order_id,
                'passport_number' => $inputData["passport_$index"]
            ]);

            if ($existingTraveler) {
                // Sanitize existing name for directory comparison
                $sanitizedExistingName = str_replace(' ', '-', $existingTraveler['name']);
                if ($sanitizedExistingName !== $sanitizedName) {
                    $oldDir = "$orderDir/$sanitizedExistingName";
                    if (is_dir($oldDir) && !is_dir($travelerDir)) {
                        rename($oldDir, $travelerDir);
                    }
                }
            }
        } else {
            if (!is_dir($travelerDir)) mkdir($travelerDir, 0777, true);
        }
    }
}

// Abort if validation errors
if (!empty($errors)) {
    respondWithJson(['status' => 'error', 'errors' => $errors], 400);
}

// Save or Update data
foreach ($travelersData as $traveler) {
    $existingTraveler = $database->get('travelers', '*', [
        'AND' => [
            'order_id' => $traveler['order_id'],
            'passport_number' => $traveler['passport']
        ]
    ]);

    if ($existingTraveler) {
        if ($action === 'update') {
            $updateResult = $database->update('travelers', [
                'name' => $traveler['name'],
                'date_of_birth' => $traveler['dob'],
                'nationality' => $traveler['nationality'],
                'passport_issuing_country' => $traveler['passport_country'],
                'is_finished' => $is_finished,
                'edited_at' => date('Y-m-d H:i:s')
            ], [
                'AND' => [
                    'order_id' => $traveler['order_id'],
                    'passport_number' => $traveler['passport']
                ]
            ]);

            if ($updateResult->rowCount() > 0) {
                $insertSuccess = true;
            }
        }
    } else {
        $insertResult = $database->insert('travelers', [
            'order_id' => $traveler['order_id'],
            'name' => $traveler['name'],
            'passport_number' => $traveler['passport'],
            'date_of_birth' => $traveler['dob'],
            'nationality' => $traveler['nationality'],
            'passport_issuing_country' => $traveler['passport_country'],
            'is_finished' => $is_finished
        ]);

        if ($insertResult->rowCount() > 0) {
            $insertSuccess = true;
        }
    }
}

// Final response
if ($insertSuccess) {
    respondWithJson(['status' => 'success', 'message' => 'Traveler data processed successfully']);
} else {
    respondWithJson(['status' => 'error', 'message' => 'No data was inserted or updated.']);
}
