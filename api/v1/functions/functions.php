<?php
// Determine if running in development mode
define('DEV_MODE', ($_SERVER['SERVER_NAME'] === 'localhost'));

// Set CORS policy
header("Access-Control-Allow-Origin: " . (DEV_MODE ? "*" : "https://fayyaztravels.com"));
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("Content-Security-Policy: default-src 'self'");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
header("Referrer-Policy: no-referrer-when-downgrade");
header("Permissions-Policy: geolocation=(), microphone=(), camera=()");

// Define allowed hosts
$allowedHosts = DEV_MODE ? ['localhost'] : ['fayyaztravels.com', 'www.fayyaztravels.com'];

// Validate request
function isValidRequest($uuid, $hu, $host): bool
{
    $allowedMethods = ['POST', 'GET', 'PUT', 'DELETE'];
    return in_array($_SERVER['REQUEST_METHOD'], $allowedMethods) &&
        !empty($uuid) &&
        !empty($hu) &&
        in_array(strtolower($host), $GLOBALS['allowedHosts']);
}

function getErrorDetails($uuid, $hu, $host): array
{
    $errors = [];
    if (!in_array($_SERVER['REQUEST_METHOD'], ['POST', 'GET', 'PUT', 'DELETE'])) {
        $errors[] = "Invalid method: {$_SERVER['REQUEST_METHOD']}";
    }
    if (empty($uuid)) {
        $errors[] = "UUID not found";
    }
    if (empty($hu)) {
        $errors[] = "Hashed UUID not provided";
    }
    if (!in_array(strtolower($host), $GLOBALS['allowedHosts'])) {
        $errors[] = "Invalid host: $host";
    }
    return $errors;
}

// Get host validation
$host = $_SERVER['HTTP_HOST'] ?? '';
if (!in_array(strtolower($host), $allowedHosts)) {
    exit(json_encode(["error" => "Invalid host"]));
}

// CSRF protection
function setupCSRFProtection()
{
    if (!isset($_COOKIE['site_session'])) {
        setcookie('site_session', bin2hex(random_bytes(16)), [
            'expires' => time() + 86400,
            'path' => '/',
            'secure' => !DEV_MODE,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
    }

    if (in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT', 'DELETE'])) {
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        $originHost = parse_url($origin, PHP_URL_HOST) ?: '';
        $refererHost = parse_url($referer, PHP_URL_HOST) ?: '';

        if (!in_array($originHost, $GLOBALS['allowedHosts']) && !in_array($refererHost, $GLOBALS['allowedHosts'])) {
            http_response_code(403);
            exit(json_encode(['error' => 'CSRF protection: Invalid origin']));
        }
    }
}
setupCSRFProtection();

// Secure response function
function respondWithJson(array $response, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Input sanitization
function sanitizeInput($data): string
{
    $data = preg_replace('/[\x00-\x1F\x7F]/u', '', trim($data));
    return function_exists('normalizer_normalize') ? normalizer_normalize($data, Normalizer::FORM_C) : $data;
}



// Get real IP
function getRealIpAddress(): string
{
    $headers = ['HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
    foreach ($headers as $header) {
        if (!empty($_SERVER[$header]) && filter_var($_SERVER[$header], FILTER_VALIDATE_IP)) {
            return explode(',', $_SERVER[$header])[0];
        }
    }
    return '0.0.0.0';
}

// Rate Limiting to prevent abuse
function rateLimit($maxRequests = 100, $timeFrame = 60)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $currentTime = time();

    if (!isset($_SESSION['rate_limit'])) {
        $_SESSION['rate_limit'] = ['count' => 1, 'start_time' => $currentTime];
    } else {
        $_SESSION['rate_limit']['count']++;
    }

    if ($currentTime - $_SESSION['rate_limit']['start_time'] > $timeFrame) {
        $_SESSION['rate_limit'] = ['count' => 1, 'start_time' => $currentTime];
    }

    if ($_SESSION['rate_limit']['count'] > $maxRequests) {
        http_response_code(429);
        exit(json_encode(["error" => "Too many requests. Please try again later."]));
    }
}
rateLimit();

// Cloudflare Turnstile CAPTCHA verification
function verifyTurnstile($turnstileResponse)
{
    if (empty($turnstileResponse)) {
        return ["success" => false, "message" => "CAPTCHA verification failed: No response received."];
    }

    $secretKey = "0x4AAAAAABCJMrgfxxK9Hz7gP2fBYTNW6SI";
    $verifyURL = "https://challenges.cloudflare.com/turnstile/v0/siteverify";

    $userIP = $_SERVER["REMOTE_ADDR"]; // Optional, but recommended

    $data = [
        "secret" => $secretKey,
        "response" => $turnstileResponse,
        "remoteip" => $userIP,
    ];

    $ch = curl_init($verifyURL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/x-www-form-urlencoded"]);

    // Disable SSL verification (not recommended for production)
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    if ($response === false) {
        return ["success" => false, "message" => "Error: " . curl_error($ch)];
    }
    curl_close($ch);

    $result = json_decode($response, true);

    if ($result["success"]) {
        return ["success" => true, "message" => "✅ CAPTCHA verification successful."];
    } else {
        $errorMessages = !empty($result["error-codes"]) ? implode(", ", $result["error-codes"]) : "Unknown error";
        return ["success" => false, "message" => "❌ CAPTCHA verification failed: " . htmlspecialchars($errorMessages)];
    }
}
