<?php
// router.php

// ======================
// Security Configuration
// ======================
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");

// ================
// Error Handling
// ================
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// =================
// Helper Functions
// =================
if (!function_exists('e')) {
    function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}

function compileRoutePattern(string $path, array $constraints = []): string
{
    $pattern = preg_replace_callback('/\{(\w+)\}/', function ($matches) use ($constraints) {
        $param = $matches[1];
        $regex = $constraints[$param] ?? '[^/]+';
        return "(?<{$param}>{$regex})";
    }, $path);

    return '#^' . $pattern . '$#';
}

// ==================
// Request Processing
// ==================
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Normalize URI
$baseDir = dirname($_SERVER['SCRIPT_NAME']);
$requestUri = substr($requestUri, strlen($baseDir));
$requestUri = rtrim($requestUri, '/') ?: '/';

// ==============
// Route Defs
// ==============
$routes = [
    ['path' => '/', 'methods' => ['GET'], 'view' => 'home'],
    ['path' => '/home', 'methods' => ['GET'], 'view' => 'home'],
    ['path' => '/notifications', 'methods' => ['GET'], 'view' => 'notifications'],
    ['path' => '/settings', 'methods' => ['GET'], 'view' => 'settings'],
    ['path' => '/applications', 'methods' => ['GET'], 'view' => 'applications'],
    ['path' => '/sitemap-html', 'methods' => ['GET'], 'view' => 'sitemap-index'],
    ['path' => '/auth/google_auth', 'methods' => ['GET'], 'view' => 'google-auth'],
    ['path' => '/google', 'methods' => ['GET'], 'view' => 'google'],
    ['path' => '/start-application', 'methods' => ['GET'], 'view' => 'start-application'],
    ['path' => '/dashboard', 'methods' => ['GET'], 'view' => 'dashboard'],
    ['path' => '/logout', 'methods' => ['GET'], 'view' => 'user/logout'],
    ['path' => '/auth/forgot-password', 'methods' => ['GET'], 'view' => 'user/forgot-password'],
    ['path' => '/auth/email_verification', 'methods' => ['GET'], 'view' => 'user/email_verification'],
    ['path' => '/changelog', 'methods' => ['GET'], 'view' => 'changelog'],
    ['path' => '/settings', 'methods' => ['GET'], 'view' => 'settings'],
    ['path' => '/tazapay_api', 'methods' => ['GET'], 'view' => 'tazapay'],
    ['path' => '/payment/pay', 'methods' => ['GET'], 'view' => 'payment/create_payin'],
    ['path' => '/payment/compare_payload', 'methods' => ['GET'], 'view' => 'payment/compare_payload'],
    ['path' => '/payment/success', 'methods' => ['GET'], 'view' => 'payment/success'],
    ['path' => '/payment/error', 'methods' => ['GET'], 'view' => 'payment/error'],
    ['path' => '/payment/webhook', 'methods' => ['GET'], 'view' => 'payment/webhook'],
    ['path' => '/truecaller_callback', 'methods' => ['GET'], 'view' => 'callback'],
    ['path' => '/user/{id}', 'methods' => ['GET'], 'view' => 'user/profile', 'constraints' => ['id' => '\d+']],
    ['path' => '/auth/signup', 'methods' => ['GET'], 'view' => 'user/signup'],
    ['path' => '/auth/login', 'methods' => ['GET'], 'view' => 'user/login'],
    ['path' => '/profile', 'methods' => ['GET'], 'view' => 'user/profile'],
    ['path' => '/corporate-visa', 'methods' => ['GET'], 'view' => 'corporate-visa'],
    ['path' => '/country/{country}', 'methods' => ['GET'], 'view' => 'apply-visa', 'constraints' => ['country' => '[a-z0-9-]+']],
    ['path' => '/search', 'methods' => ['GET'], 'view' => 'search_query'],
    ['path' => '/support', 'methods' => ['GET'], 'view' => 'support'],
    ['path' => '/blog/post/{slug}', 'methods' => ['GET'], 'view' => 'blog/post', 'constraints' => ['slug' => '[a-z0-9-]+']],
    ['path' => '/pages/{slug}', 'methods' => ['GET'], 'view' => 'pages/page', 'constraints' => ['slug' => '[a-z0-9-]+']],
    [
        'path' => '/application/{order_id}/{step}',
        'methods' => ['GET'],
        'view' => 'application',
        'constraints' => [
            'order_id' => '[A-Z0-9-]+',  // Supports uppercase letters
            'step'     => '[a-zA-Z0-9-]+' // Supports both uppercase & lowercase
        ]
    ]

];

// ================
// Route Matching
// ================
foreach ($routes as $route) {
    if (!in_array($requestMethod, $route['methods'])) continue;

    $pattern = compileRoutePattern(
        $route['path'],
        $route['constraints'] ?? []
    );

    if (preg_match($pattern, $requestUri, $matches)) {
        $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

        $viewFile = $route['view'] . '.php';
        $viewPath = realpath(__DIR__ . '/views/' . $viewFile);
        $allowedDir = realpath(__DIR__ . '/views');

        if (!$viewPath || strpos($viewPath, $allowedDir) !== 0) {
            http_response_code(500);
            include __DIR__ . '/views/500.php';
            exit;
        }

        if (file_exists($viewPath)) {
            include $viewPath;
            exit;
        }

        http_response_code(404);
        include __DIR__ . '/views/404.php';
        exit;
    }
}

// =============
// 404 Handler
// =============
http_response_code(404);
include __DIR__ . '/views/404.php';
