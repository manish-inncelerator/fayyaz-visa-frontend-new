<?php

// connect to database
require_once "database.php";

// fetch latest pages and countries
$pages = $database->select("pages", "*", ["ORDER" => ["id" => "DESC"]]);
$countries = $database->select("visa_countries", "*", ["ORDER" => ["id" => "DESC"]]);

// create xml sitemap
$sitemap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$sitemap .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

// add pages
foreach ($pages as $page) {
    $sitemap .= "<url>\n";
    $sitemap .= "<loc>https://fayyaztravels.com/visa/pages/" . htmlspecialchars($page['pageSlug']) . "</loc>\n"; // Fixed key from '[pageSlug]' to 'pageSlug'
    $lastmod = !empty($page['updated_at']) ? date('Y-m-d', strtotime($page['updated_at'])) : date('Y-m-d'); // Default to current date if null
    $sitemap .= "<lastmod>" . $lastmod . "</lastmod>\n";
    $sitemap .= "</url>\n";
}

// add countries
foreach ($countries as $country) {
    $sitemap .= "<url>\n";
    $sitemap .= "<loc>https://fayyaztravels.com/visa/country/apply-for-" . htmlspecialchars($country['country_name']) . "-visa-online</loc>\n";
    $lastmod = !empty($country['edited_date']) ? date('Y-m-d', strtotime($country['edited_date'])) : date('Y-m-d'); // Default to current date if null
    $sitemap .= "<lastmod>" . $lastmod . "</lastmod>\n";
    $sitemap .= "</url>\n";
}

$sitemap .= "</urlset>\n";

// save to sitemap.xml (in public root directory) and overwrite if it exists
file_put_contents('sitemap.xml', $sitemap, LOCK_EX);

// Optional: Output message
echo "Sitemap successfully generated and saved to sitemap.xml.";
