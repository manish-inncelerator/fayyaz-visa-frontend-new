<?php
function minify($buffer)
{
    // Preserve JSON-LD and external scripts
    $buffer = preg_replace_callback(
        '/<script\b[^>]*>(.*?)<\/script>/is',
        fn($matches) =>
        strpos($matches[0], 'type="application/ld+json"') !== false || strpos($matches[0], 'src=') !== false
            ? $matches[0] // Preserve the script tag if it's JSON-LD or has a src attribute
            : '<script>' . preg_replace(
                [
                    '/\/\*.*?\*\//s',      // Remove multiline comments
                    '/\/\/[^\r\n]*/',      // Remove single-line comments
                    '/\s+/',               // Collapse multiple whitespaces
                    '/[\r\n\t]/'           // Remove newlines and tabs
                ],
                ['', '', ' ', ''],
                $matches[1]
            ) . '</script>',
        $buffer
    );

    // Preserve style tags and minify CSS
    $buffer = preg_replace_callback(
        '/<style\b[^>]*>(.*?)<\/style>/is',
        fn($matches) =>
        '<style>' . preg_replace(
            [
                '/\/\*.*?\*\//s',       // Remove multiline comments
                '/\s+/',                // Collapse multiple whitespaces
                '/[\r\n\t]/'            // Remove newlines and tabs
            ],
            ['', ' ', ''],
            $matches[1]
        ) . '</style>',
        $buffer
    );

    // Preserve URLs in <a>, <img>, and <script src> tags
    $buffer = preg_replace_callback(
        '/<(a|img|script)\b[^>]*\s(href|src)\s*=\s*["\']([^"\']+)["\'][^>]*>/is',
        fn($matches) => $matches[0], // Preserve the entire tag with the URL
        $buffer
    );

    // Remove HTML comments and unnecessary whitespaces
    return preg_replace(
        ['/<!--.*?-->/s', '/\s+/', '/>\s+</'],
        ['', ' ', '><'],
        str_replace(["\r\n", "\r", "\n", "\t"], '', $buffer)
    );
}

// Start output buffering
ob_start("minify");
