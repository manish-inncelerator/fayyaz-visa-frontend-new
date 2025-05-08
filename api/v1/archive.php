<?php

session_start();

require '../../database.php';
require '../../sendMail.php';
require 'functions/validate_hu.php';

header('Content-Type: application/json');
$inputData = json_decode(file_get_contents("php://input"), true);

$uuid = $_SESSION['uuid'] ?? null;
$headers = getallheaders();
$hu = $headers['HU'] ?? $headers['Hu'] ?? $headers['hu'] ?? null;
$host = parse_url('http://' . ($_SERVER['HTTP_HOST'] ?? ''), PHP_URL_HOST);

// Validate request & HU
if (!isValidRequest($uuid, $hu, $host) || !isHuValid($uuid, $hu)) {
    respondWithJson(["error" => "Invalid request"], 400);
    exit;
}

$orderId = sanitizeInput($inputData['orderId'] ?? '');

// get user id
$getUseId = $database->get("orders", "order_user_id", ["order_id" => $orderId]);

// get user email
$getUserEmail = $database->get("users", "email", ["user_id" => $getUseId]);


if (!$orderId) {
    respondWithJson(["error" => "Missing required fields"], 400);
    exit;
}

try {
    $archive = $database->update("orders", ["is_archive" => 1], ["order_id" => $orderId]);

    if ($archive->rowCount() > 0) {
        // Send email notification first
        $emailSent = sendEmail(
            $getUserEmail,
            "Order #$orderId Archived",
            'default',
            'Hi! Your order #' . $orderId . ' has been archived.',
            true,
            "https://fayyaztravels.com/visa/applications"
        );

        // Check if email was sent successfully
        if ($emailSent) {
            respondWithJson(["success" => "Order archived and email sent"], 200);
        } else {
            respondWithJson(["warning" => "Order archived, but email not sent"], 500);
        }
    } else {
        respondWithJson(["error" => "Failed to archive order"], 500);
    }
} catch (Exception $e) {
    respondWithJson(["error" => "Database error: " . $e->getMessage()], 500);
}
