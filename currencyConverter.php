<?php
function getExchangeRate(string $to, string $from): ?float
{
    $cacheDir = __DIR__ . "/cache";
    $cacheFile = "$cacheDir/{$to}_{$from}.json";
    $trafficFile = "$cacheDir/traffic.json";

    // **Ensure cache directory exists**
    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0777, true);
    }

    $now = time();
    $trafficData = [];

    // **Load and clean traffic data efficiently**
    if (file_exists($trafficFile)) {
        $trafficData = json_decode(file_get_contents($trafficFile), true) ?? [];
    }

    $lastCleanup = $trafficData['last_cleanup'] ?? 0;
    if ($now - $lastCleanup > 300) { // Cleanup every 5 minutes
        $trafficData['requests'] = array_filter($trafficData['requests'] ?? [], fn($timestamp) => ($now - $timestamp) <= 600);
        $trafficData['last_cleanup'] = $now;
    }

    // **Log new request (only if needed)**
    $trafficData['requests'][] = $now;
    // Save traffic data after every request instead of every 10 requests
    file_put_contents($trafficFile, json_encode($trafficData, JSON_UNESCAPED_SLASHES));

    $requestCount = count($trafficData['requests']);

    // **Dynamic cache duration based on traffic**
    $cacheDuration = $requestCount > 50 ? 300 : ($requestCount > 10 ? 900 : 3600);

    // **Use file cache if available**
    if (file_exists($cacheFile) && ($now - filemtime($cacheFile)) < $cacheDuration) {
        return json_decode(file_get_contents($cacheFile), true);
    }

    // **Fetch exchange rate**
    $url = "http://www.exchange-rates.org/api/v2/rates/lookup?isoTo={$to}&isoFrom={$from}&amount=1&pageCode=Converter";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Timeout after 5 seconds
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    if (!isset($data['Rate']) || !is_numeric($data['Rate'])) {
        error_log("Exchange Rate Error: Invalid response");
        return null;
    }

    $rate = round((float) $data['Rate'], 2);

    // **Save rate to file cache**
    file_put_contents($cacheFile, json_encode($rate, JSON_UNESCAPED_SLASHES));

    return $rate;
}
