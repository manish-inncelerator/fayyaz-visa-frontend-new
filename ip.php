<?php

function getClientIP()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ipList[0]);
    } else {
        return $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    }
}

$apiKey = '7f3e45ad50354c41a84cf30075ed8c2d';
$clientIP = getClientIP();

// Handle dynamic IP addresses
if ($clientIP === '127.0.0.1' || $clientIP === '::1') {
    $clientIP = '8.8.8.8'; // Google Public DNS IP for testing
}

$cacheFile = __DIR__ . "/cache/{$clientIP}.json"; // Cache file path
$cacheDuration = 3600; // Cache duration in seconds (1 hour)

// Check if cached file exists and is still valid
if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheDuration) {
    // Return cached data
    header('Content-Type: application/json');
    echo file_get_contents($cacheFile);
    exit;
}

// Use a unique identifier for each user session to prevent multiple requests
session_start();
$sessionKey = 'geo_data_' . md5($clientIP); // Use md5 hash to handle dynamic IPs

if (isset($_SESSION[$sessionKey])) {
    // Return data from session if available
    header('Content-Type: application/json');
    echo json_encode($_SESSION[$sessionKey]);
    exit;
}

$url = "https://api.ipgeolocation.io/ipgeo?apiKey=$apiKey&ip=$clientIP";

// Call API
header('Content-Type: application/json'); // Set the content type to JSON
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification for testing
$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(['error' => 'Curl error: ' . curl_error($ch)]);
} else {
    $data = json_decode($response, true);
    $currencyCode = $data['currency']['code'] ?? 'SGD';
    $countryCode = $data['country_code2'] ?? 'SG'; // Get country code
    $countryEmoji = $data['country_emoji'] ?? '🇸🇬';

    // Save response to cache
    file_put_contents($cacheFile, json_encode(['currencyCode' => $currencyCode, 'countryCode' => $countryEmoji . ' ' . $countryCode]));

    // Save response to session
    $_SESSION[$sessionKey] = ['currencyCode' => $currencyCode, 'countryCode' => $countryEmoji . ' ' . $countryCode];

    // Return currency code and country code with emoji as JSON
    echo json_encode(['currencyCode' => $currencyCode, 'countryCode' => $countryEmoji . ' ' . $countryCode]);
}

curl_close($ch);
