<?php
session_start();

require '../../database.php';
require 'functions/validate_hu.php';

header('Content-Type: application/json');

// Fetch input data
$inputData = json_decode(file_get_contents("php://input"), true);

$uuid = $_SESSION['uuid'] ?? null;

// Fetch HU from headers (case-insensitive)
$headers = getallheaders();
$hu = $headers['HU'] ?? $headers['Hu'] ?? $headers['hu'] ?? null;

// Host validation

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = parse_url($scheme . '://' . ($_SERVER['HTTP_HOST'] ?? ''), PHP_URL_HOST);
// Debugging information (only if DEV_MODE is enabled)
if (DEV_MODE) {
    error_log(json_encode(["debug" => ["uuid" => $uuid, "hu" => $hu, "host" => $host]]));
}

// Validate request
if (!isValidRequest($uuid, $hu, $host)) {
    respondWithJson([
        "error" => "Invalid request",
        "debug" => getErrorDetails($uuid, $hu, $host)
    ], 400);
}

// Validate HU
if (!isHuValid($uuid, $hu)) {
    respondWithJson(["error" => "Invalid HU"], 401);
}

// Fetch and sanitize order_id from GET parameters
$order_id = strip_tags(stripslashes(trim($_GET['order_id'] ?? '')));

if (empty($order_id)) {
    respondWithJson(["error" => "Order ID is required"], 400);
}
// Fetch traveler data from the database
$travelers = $database->select("travelers", [
    'order_id',
    'name',
    'passport_number',
    'nationality',
    'date_of_birth',
    'passport_issuing_country'
], [
    "order_id" => $order_id
]);


if (empty($travelers)) {
    respondWithJson(["error" => "No travelers found for the given Order ID"]);
}

// Return traveler data in JSON format
respondWithJson(["data" => $travelers], 200);
