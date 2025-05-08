<?php
header('Content-Type: text/plain');

// Retrieve and sanitize incoming content
$rawContent     = filter_input(INPUT_GET, 'content', FILTER_UNSAFE_RAW) ?? '';
$decodedContent = urldecode($rawContent);

// Convert HTML links (<a href="...">text</a>) into plain‐text clickable form: "text (URL)"
$decodedContent = preg_replace_callback(
    '/<a\s+[^>]*href=[\'"]([^\'"]+)[\'"][^>]*>(.*?)<\/a>/i',
    function (array $m): string {
        $url  = $m[1];
        $text = strip_tags($m[2]);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        return trim($text) . ' (' . $url . ')';
    },
    $decodedContent
);

// Strip any remaining HTML tags
$sanitizedContent = strip_tags($decodedContent);
// Decode HTML entities and remove non-printables except newline, CR and tab
$sanitizedContent = html_entity_decode($sanitizedContent, ENT_QUOTES | ENT_HTML5, 'UTF-8');
$sanitizedContent = preg_replace('/[^\x09\x0A\x0D\x20-\x7E]/', '', $sanitizedContent);

// Build plain-text email
$plain_text  = "Fayyaz Travels\n";
$plain_text .= "==============\n\n";
$plain_text .= $sanitizedContent . "\n\n";
$plain_text .= "Contact Information:\n";
$plain_text .= "-------------------\n";
$plain_text .= "Fayyaz Travels\n";
$plain_text .= "435 Orchard Rd, #11-00 Wisma Atria, Singapore 238877\n";
$plain_text .= "Phone: +65 6235 2900\n";
$plain_text .= "Email: mailto:info@fayyaztravels.com\n";
$plain_text .= "Website: https://fayyaztravels.com\n\n";
$plain_text .= "© " . date('Y') . " Fayyaz Travels. All rights reserved.\n";
$plain_text .= "This email was sent to you because you are a valued customer of Fayyaz Travels.\n";
$plain_text .= "If you received this email by mistake, please delete it and notify us immediately.\n";
$plain_text .= "To unsubscribe: mailto:unsubscribe@fayyaztravels.com\n";

echo $plain_text;
