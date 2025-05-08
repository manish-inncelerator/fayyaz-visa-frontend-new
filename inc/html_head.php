<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require 'vendor/autoload.php';
require 'UuidHasher.php';

use Ramsey\Uuid\Uuid;

// Determine the base URL
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://") . $_SERVER['HTTP_HOST'];

// Check if the environment is local
$isLocal = in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1']) || preg_match('/\.local$/', $_SERVER['HTTP_HOST']);

// Set the base URL based on the environment
$url = $isLocal ? '/visa_f/' : '/visa/';


// Generate and store UUID if not already in session
if (!isset($_SESSION['uuid'])) $_SESSION['uuid'] = Uuid::uuid4()->toString();

// Hash the UUID using HMAC and output
$hu = sha1($_SESSION['uuid']);


// HTML Header Function
function html_head(
    $title = "Fayyaz Travels",
    $bodyBgColor = "",
    $includeFontAwesome = false,
    $externalStyleSheets = [],
    $includeNotifilix = false,
    $includeFpFavicon = false,
    $includeSeo = false
) {
    global $marginTop;

    // Parse current URL path
    $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    $segments = explode('/', $path);

    // Default margin
    $marginTop = '90px';

    // Convert segments to lowercase for case-insensitive matching
    $segments = array_map('strtolower', $segments);


    if (isset($segments[1]) && $segments[1] === 'auth') {
        if (in_array('login', $segments) || in_array('start-application', $segments) || in_array('forgot-password', $segments)) {
            $marginTop = '0px';
        }
    } elseif (in_array('pages', $segments)) {
        $marginTop = '90px';
    } elseif (in_array('home', $segments) || in_array('visa', $segments) || in_array('visa_f', $segments) || $path === '') {
        $marginTop = '65px';
    } else {
        $marginTop = '90px'; // Default margin for all other pages
    }

    $marginStyle = "style=\"margin-top: {$marginTop};\"";



    // SEO
    global $seoContent, $schemaContent, $url;

    // Conditionally include FontAwesome
    $fontAwesome = $includeFontAwesome ? '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">' : '';

    // Conditionally include fingerprint tracking favicon
    $fpFavicon = $includeFpFavicon
        ? '<link rel="icon" href="data:image/x-icon;base64,AAABAAEAEBAAAAEAIABoBAAAJgAAACAgAAABACAAaAcAAIAEAABAAAADAAAAEAAAAABAAgAA..." type="image/x-icon">'
        : '';

    // Conditionally include notiflix
    $notiflix = $includeNotifilix ? '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/src/notiflix.min.css">' : '';


    // Conditionally set body class
    $bodyClass = $bodyBgColor ? "class=\"$bodyBgColor\"" : "";

    // Include SEO and Schema Markup
    $page = basename($_SERVER['PHP_SELF']);
    $seoFile = "seo/{$page}.php";
    $schemaFile = "schema-markup/{$page}.php";

    $seoContent = '';
    $schemaContent = '';

    // Include SEO content if the file exists
    if (file_exists($seoFile)) {
        ob_start();
        include $seoFile;
        $seoContent = ob_get_clean();
    }

    // Include Schema Markup if the file exists
    if (file_exists($schemaFile)) {
        ob_start();
        include $schemaFile;
        $schemaContent = ob_get_clean();
    }

    $token = bin2hex(random_bytes(8));
    // Generate <link> tags for external stylesheets
    $externalCssLinks = '';
    if (!empty($externalStyleSheets)) {
        foreach ($externalStyleSheets as $stylesheet) {
            // Check if the stylesheet URL already has query parameters
            $delimiter = (strpos($stylesheet, '?') !== false) ? '&' : '?';
            $externalCssLinks .= '<link rel="stylesheet" href="' . $stylesheet . $delimiter . 'token=' . urlencode($token) . '" />' . "\n";
        }
    }

    // Preconnect, Preload, and Prefetch Links
    $resourceLinks = '
        <!-- Preconnect to third-party domains -->
        <link rel="preconnect" href="https://cdnjs.cloudflare.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link rel="preconnect" href="https://cdn.jsdelivr.net" />

        <!-- Preload critical resources -->
        <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" as="style" />
        <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" as="style" />
        <link rel="preload" href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Poppins:ital,wght@0,400;0,700;1,400;1,600;1,700&display=swap" as="style" />
        
        <!-- Preconnect for Analytics and Clarity -->
        <link rel="preconnect" href="https://www.googletagmanager.com" crossorigin>
        <link rel="preconnect" href="https://www.clarity.ms" crossorigin>


        <!-- Prefetch non-critical resources -->
    ';

    return <<<HTML
    <!doctype html>
    <html lang="en">

    <head>
        <base href="{$url}"> <!-- Base URL for relative links -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no, use-scalable=no" />
        {$includeSeo}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
        {$resourceLinks}
        {$fontAwesome}
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Poppins:ital,wght@0,400;0,700;1,400;1,600;1,700&display=swap" rel="stylesheet">
        <link rel="icon" type="image/png" href="assets/favicon/favicon-96x96.png" sizes="96x96" />
        {$fpFavicon}
        <link rel="shortcut icon" href="assets/favicon/favicon.ico" />
        <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-touch-icon.png" />
        <meta name="apple-mobile-web-app-title" content="Fayyaz Travels" />
        <!-- <link rel="stylesheet" href="assets/css/custom.css?token=$token" /> -->
        {$externalCssLinks}
        <link rel="manifest" href="site.webmanifest" />
        <title>{$title} &mdash; Fayyaz Travels</title>
        {$notiflix}
       
        <style>
            /* Apply IBM Plex Serif to headings */
            h1,
            h2,
            h3,
            h4,
            h5,
            h6,
            .plexFont {
                font-family: "Poppins", system-ui, serif !important;
                font-weight: 600;
                /* Semi-bold for headings */
            }

            /* Apply Funnel Display to paragraphs, buttons, and links */
            p,
            button,
            a,
            form,
            .alterFont,
            div,
            .modal-body,
            body {
                font-family: "DM Sans", sans-serif !important;
                font-weight: 400;
                /* Regular weight for text */
            }

            :root {
                /* Brown Color Palette */
                --brown: #543019;
                --brown-light: #7a4a2d;
                /* Lighter shade */
                --brown-lighter: #a06441;
                /* Even lighter shade */
                --brown-dark: #3d2412;
                /* Darker shade */
                --brown-darker: #27180c;
                /* Even darker shade */

                /* Golden Color Palette */
                --golden: #af8700;
                --golden-light: #d6a500;
                /* Lighter shade */
                --golden-lighter: #ffc300;
                /* Even lighter shade */
                --golden-dark: #876900;
                /* Darker shade */
                --golden-darker: #5f4b00;
                /* Even darker shade */

                /* Blue Color Palette */
                --blue: #14385C;
                /* Base color */
                --blue-light: #3a6e8f;
                /* Lighter shade */
                --blue-lighter: #6699b3;
                /* Even lighter shade */
                --blue-dark: #0c2c44;
                /* Darker shade */
                --blue-darker: #081c2d;
                /* Even darker shade */
            }

          .page-item.active {
            background-color: var(--golden) !important;  /* Custom golden background */
            border-color: var(--golden) !important;      /* Border matches the background */
            color: #fff !important;                      /* White text for contrast */
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);    /* Optional: subtle shadow for depth */
            border-radius: 0.375rem;                     /* Smooth rounded corners */
            }




            /* Golden Links */
            .link-golden {
                color: var(--golden);
                text-decoration: none;
                transition: color 0.3s ease, text-decoration 0.3s ease;
            }

            .link-golden:hover {
                color: var(--golden-dark);
                text-decoration: underline;
            }

            .link-golden:focus {
                color: var(--golden-darker);
                outline: 2px solid var(--golden);
            }

            .link-golden:active {
                color: var(--brown);
            }

            .link-golden:visited {
                color: var(--brown-dark);
            }

            /* Brown Links */
            .link-brown {
                color: var(--brown);
                text-decoration: none;
                transition: color 0.3s ease, text-decoration 0.3s ease;
            }

            .link-brown:hover {
                color: var(--brown-dark);
                text-decoration: underline;
            }

            .link-brown:focus {
                color: var(--brown-darker);
                outline: 2px solid var(--brown);
            }

            .link-brown:active {
                color: var(--golden);
            }

            .link-brown:visited {
                color: var(--golden-dark);
            }

            /* Brown Text Colors */
            .text-brown {
                color: var(--brown);
            }

            .text-brown-light {
                color: var(--brown-light);
            }

            .text-brown-lighter {
                color: var(--brown-lighter);
            }

            .text-brown-dark {
                color: var(--brown-dark);
            }

            .text-brown-darker {
                color: var(--brown-darker);
            }

            /* Golden Text Colors */
            .text-golden {
                color: var(--golden);
            }

            .text-golden-light {
                color: var(--golden-light);
            }

            .text-golden-lighter {
                color: var(--golden-lighter);
            }

            .text-golden-dark {
                color: var(--golden-dark);
            }

            .text-golden-darker {
                color: var(--golden-darker);
            }

            /* Brown Buttons */
            .btn-brown {
                background-color: var(--brown);
                color: white;
            }

            .btn-brown:hover {
                background-color: var(--brown-dark);
                color: white;
            }

            .btn-brown:focus {
                outline: 2px solid var(--brown-darker);
                color: white;
            }

            .btn-brown:active {
                background-color: var(--brown-darker);
                color: white;
            }

            /* Blue Buttons */
            .btn-blue {
                background-color: var(--blue);
                color: white;
            }

            .btn-blue:hover {
                background-color: var(--blue-dark);
                color: white;
            }

            .btn-blue:focus {
                outline: 2px solid var(--blue-darker);
                color: white;
            }

            .btn-blue:active {
                background-color: var(--blue-darker);
                color: white;
            }

            /* Golden Buttons */
            .btn-golden {
                background-color: var(--golden);
                color: white;
            }

            .btn-golden:hover {
                background-color: var(--golden-dark);
                color: white;
            }

            .btn-golden:focus {
                outline: 2px solid var(--golden-darker);
                color: white;
            }

            .btn-golden:active {
                background-color: var(--golden-darker);
                color: white;
            }

            /* Outline Buttons */
            .btn-outline-brown {
                background-color: transparent;
                border: 1px solid var(--brown);
                color: var(--brown);
            }

            .btn-outline-brown:hover {
                background-color: var(--brown);
                color: white;
            }

            /* Outline Buttons */
            .btn-outline-blue {
                background-color: transparent;
                border: 1px solid var(--blue);
                color: var(--blue);
            }

            .btn-outline-blue:hover {
                background-color: var(--blue);
                color: white;
            }

            .btn-outline-brown:focus {
                outline: 2px solid var(--brown-darker);
            }

            .btn-outline-brown:active {
                background-color: var(--brown);
                /* Prevent transparency, add background color */
                border-color: var(--brown);
                /* Keep the border color same as background */
                color: white;
                /* Ensure text color stays white */
            }

            .btn-outline-golden {
                background-color: transparent;
                border: 1px solid var(--golden);
                color: var(--golden);
            }

            .btn-outline-golden:hover {
                background-color: var(--golden);
                color: white;
            }

            .btn-outline-golden:focus {
                outline: 2px solid var(--golden-darker);
            }

            .btn-outline-golden:active {
                background-color: var(--golden);
                /* Prevent transparency, add background color */
                border-color: var(--golden);
                /* Keep the border color same as background */
                color: white;
                /* Ensure text color stays white */
            }



            /* cta button */
            .cta-button {
                background-color: var(--blue);
                color: #fff;
                border: none;
                /* Optional: Remove border if needed */
                transition: background-color 0.3s ease-in-out, color 0.3s ease-in-out;
                /* Add transition */
            }

            .cta-button:hover {
                background-color: var(--golden);
                color: #fff;
            }


            /* Glassy Navbar Style */
            .navbar-glassy {
                background: rgba(255, 255, 255, 0.2);
                /* Semi-transparent white */
                backdrop-filter: blur(10px);
                /* Apply blur to background */
                -webkit-backdrop-filter: blur(10px);
                /* Safari support */
                border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            }

            /* Optional: Adjust link colors for better contrast */
            .navbar-glassy .nav-link {
                transition: color 0.3s ease;
            }
        </style>


        <!-- Google Analytics - Fast and SEO-Friendly -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-5P3XR1QL29"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', 'G-5P3XR1QL29');
        </script>

        <!-- Microsoft Clarity -->
        <script type="text/javascript">
            (function(c,l,a,r,i,t,y){
                c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
                t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
                y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
            })(window, document, "clarity", "script", "r4h0l3jvjr");
        </script>
    </head>
       
    <body {$bodyClass} {$marginStyle}>
HTML;
}
