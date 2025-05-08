<?php
$client_id = "YOUR_APP_KEY";
$client_secret = "YOUR_APP_SECRET";
$redirect_uri = "YOUR_REDIRECT_URL";

if (isset($_GET['authorization_code'])) {
    $auth_code = $_GET['authorization_code'];

    // Exchange authorization code for access token
    $token_url = "https://api4.truecaller.com/v1/apps/token";

    $post_data = [
        'code' => $auth_code,
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'grant_type' => 'authorization_code',
        'redirect_uri' => $redirect_uri,
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $token_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/x-www-form-urlencoded"]);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if (isset($result['access_token'])) {
        $access_token = $result['access_token'];

        // Fetch user profile
        $user_url = "https://api4.truecaller.com/v1/me";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $user_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $access_token"]);

        $user_response = curl_exec($ch);
        curl_close($ch);

        $user_data = json_decode($user_response, true);
        print_r($user_data); // You can store this data in your database
    } else {
        echo "Error fetching access token!";
    }
} else {
    echo "No authorization code received!";
}
