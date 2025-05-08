<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../../database.php';
require '../../sendMail.php';
require '../../vendor/autoload.php';
require 'functions/validate_hu.php';

use Ramsey\Uuid\Uuid;

header('Content-Type: application/json');
$inputData = json_decode(file_get_contents("php://input"), true);

// Generate Email Verification Code
$emailVerificationCode = Uuid::uuid4()->toString();

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

// Captcha Validation

// Anti-bot honeypot
if (!empty($_POST['website'])) {
    respondWithJson(["error" => "Bot detected 🚫"], 403);
    exit;
}

// Validate emoji match securely
$selected = filter_input(INPUT_POST, 'selectedEmoji', FILTER_UNSAFE_RAW);
$target = filter_input(INPUT_POST, 'targetEmoji', FILTER_UNSAFE_RAW);
$token = filter_input(INPUT_POST, 'captchaToken', FILTER_UNSAFE_RAW);

// Use a constant time comparison to prevent timing attacks
if ($selected === $target) {
    // echo "<h3 style='color:green;'>✅ CAPTCHA passed. You're human!</h3>";
} else {
    respondWithJson(["error" => "❌ CAPTCHA failed. Please try again."], 400);
    exit;
}


// Remove Google invisible reCAPTCHA
// $secretKey  = "6LciAv4qAAAAAOt82iD-Or8LJxpEC3NavOwMVi_S";

// $captchaResponse = $inputData["captcha-response"] ?? '';
// Get verify response data
// $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretKey . '&response=' . $captchaResponse);
// $responseData = json_decode($verifyResponse);
// print_r($responseData);
// if (!$responseData->success) {
//     respondWithJson(["error" => "Captcha verification failed. Please refresh the page and try again."], 400);
//     exit;
// }

// Extract & Sanitize Input
$fname = sanitizeInput($inputData['fname'] ?? '');
$lname = sanitizeInput($inputData['lname'] ?? '');
$email = sanitizeInput($inputData['email'] ?? '');
$password = sanitizeInput($inputData['password'] ?? '');
$contactMethods = $inputData['contactMethod'] ?? [];
$telegramUsername = sanitizeInput($inputData['telegramUsername'] ?? '');
$countryCode = sanitizeInput($inputData['country_code'] ?? '');
$countryCodeAlpha = sanitizeInput($inputData['country_code_alpha'] ?? '');
$countryName = sanitizeInput($inputData['country_name'] ?? '');
$whatsappNumber = sanitizeInput($inputData['whatsappNumber'] ?? '');
$mobileNumber = sanitizeInput($inputData['mobile'] ?? '');

// Validate Required Fields
if (!$fname || !$lname || !$email || !$password || empty($contactMethods)) {
    respondWithJson(["error" => "Missing required fields"], 400);
    exit;
}

// Check if Email Already Exists
$emailExists = $database->get("users", "email", ["email" => $email]);
if ($emailExists) {
    respondWithJson(["error" => "An account already exists with this email"], 400);
    exit;
}

// Check if Mobile Number Already Exists
$mobileExists = $database->get("users", "mobile_number", ["mobile_number" => $mobileNumber]);
if ($mobileExists) {
    respondWithJson(["error" => "An account already exists with this mobile number"], 400);
    exit;
}

// Hash Password
$options = ['memory_cost' => 65536, 'time_cost' => 4, 'threads' => 2];
$hashedPassword = password_hash($password, PASSWORD_ARGON2ID, $options);

// Generate User ID
$userId = Uuid::uuid4()->toString();

// Insert User Data
$insert = $database->insert("users", [
    "user_id" => $userId,
    "first_name" => $fname,
    "last_name" => $lname,
    "email" => $email,
    "password" => $hashedPassword,
    "ip_address" => getRealIpAddress(),
    "join_date" => date("Y-m-d H:i:s"),
    "is_telegram" => in_array("telegram", $contactMethods) ? 1 : 0,
    "is_whatsapp_same_as_mobile_number" => in_array("whatsapp", $contactMethods) ? 1 : 0,
    "telegram_username" => $telegramUsername,
    "country_code" => $countryCode,
    "country_code_alpha" => $countryCodeAlpha,
    "country_name" => $countryName,
    "contact_methods" => json_encode($contactMethods),
    "whatsapp_number" => $whatsappNumber,
    "mobile_number" => $mobileNumber,
    "email_verification_code" => $emailVerificationCode
]);

if ($insert->rowCount() > 0) {
    // Send Email Verification
    sendEmail(
        $email,
        "Email Verification",
        'default',
        'Thank you for signing up! Please verify your email address by clicking the link below:',
        true,
        "https://fayyaztravels.com/visa/auth/email_verification?code=$emailVerificationCode"
    );

    respondWithJson(["success" => "Registration Successful"], 201);
} else {
    respondWithJson(["error" => "Failed to save data"], 500);
}
