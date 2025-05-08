<?php
session_start();

require '../../database.php';
require 'functions/validate_hu.php';
require '../../sendMail.php';


header('Content-Type: application/json');
$inputData = json_decode(file_get_contents("php://input"), true);

// Fetch UUID from session
$uuid = $_SESSION['uuid'] ?? null;

// Fetch HU header with Nginx compatibility
$headers = array_change_key_case(getallheaders(), CASE_LOWER);
$hu = $headers['hu'] ?? ($_SERVER['HTTP_HU'] ?? null);

// Get host information
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = parse_url("$scheme://" . ($_SERVER['HTTP_HOST'] ?? ''), PHP_URL_HOST);

// Validate request & HU header
if (!isValidRequest($uuid, $hu, $host) || !isHuValid($uuid, $hu)) {
    respondWithJson(["error" => "Invalid request or HU"], 401);
}

// Sanitize and validate order ID
$orderId = sanitizeInput($inputData['orderId'] ?? null);

// get user id
$getUseId = $database->get("orders", "order_user_id", ["order_id" => $orderId]);

// get user email
$getUserEmail = $database->get("users", "email", ["user_id" => $getUseId]);

if (!$orderId) {
    respondWithJson(["error" => "Invalid or missing order ID"], 400);
}


// Attempt to update archive status in DB
try {
    $archive = $database->update("orders", ["is_archive" => 0], ["order_id" => $orderId]);

    if ($archive->rowCount() > 0) {
        // Send email notification
        $emailSent = sendEmail(
            $getUserEmail,
            "Order #$orderId Unarchived",
            'default',
            'Hi! Your order #' . $orderId . ' has been unarchived.',
            true,
            "https://fayyaztravels.com/visa/applications"
        );

        if ($emailSent) {
            respondWithJson(["status" => "success", "message" => "Order unarchived and email sent"], 200);
        } else {
            respondWithJson(["status" => "warning", "message" => "Order unarchived, but email not sent"], 500);
        }
    } else {
        respondWithJson(["status" => "error", "message" => "Order not found or already unarchived"], 404);
    }
} catch (Exception $e) {
    respondWithJson(["error" => "Database error", "details" => $e->getMessage()], 500);
}
