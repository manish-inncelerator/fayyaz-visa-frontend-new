<?php
require 'database.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

defined('BASE_DIR') || die('Direct access denied');

// Sanitize and retrieve the page slug
$pageSlug = isset($params['slug']) ? trim(strip_tags(stripslashes($params['slug']))) : '';

// Fetch page details from the database
$page = !empty($pageSlug)
    ? $database->get("pages", "*", ["pageSlug" => $pageSlug, 'is_active' => 1])
    : null;

// Page name fallback if not found
$pageName = $page['pageName'] ?? 'Page Not Found';
$pageDetails = $page['pageDetails'] ?? 'Page Not Found';
$pageDescription = $page['pageDescription'] ?? 'Page Not Found';

// Include required files
require 'inc/html_head.php';
require 'inc/html_foot.php';
require 'min.php';


// SEO
require 'vendor/autoload.php';

use Melbahja\Seo\Schema;
use Melbahja\Seo\Schema\Thing;
use Melbahja\Seo\MetaTags;

$schema = new Schema(
    new Thing('Organization', [
        'url'          => 'https://fayyaztravels.com/visa',
        'logo'         => 'https://fayyaztravels.com/uploads/images/main-logo.png',
        'contactPoint' => new Thing('ContactPoint', [
            'telephone'   => '+65 6235 2900',
            'contactType' => 'customer service'
        ])
    ])
);


// write schema for this web page    
$webPageSchema = new Schema(
    new Thing('WebPage', [
        'url' => 'https://fayyaztravels.com/visa/pages/' . $pageSlug,
        'name' => 'Reset Password',
        'description' => 'Reset your password.',
        'author' => new Thing('Organization', [
            'name' => 'Fayyaz Travels',
            'url' => 'https://fayyaztravels.com/visa'
        ])
    ])
);

// schema for breadcrumb
$breadcrumbSchema = new Schema(
    new Thing('BreadcrumbList', [
        'itemListElement' => [
            new Thing('ListItem', [
                'position' => 1,
                'name' => 'Home',
                'url' => 'https://fayyaztravels.com/visa'
            ]),
            new Thing('ListItem', [
                'position' => 2,
                'name' => $pageName,
                'url' => 'https://fayyaztravels.com/visa/pages/' . $pageSlug
            ])
        ]
    ])
);

$metatags = new MetaTags();

$metatags
    ->title($pageName . ' — Fayyaz Travels')
    ->description($pageDescription)
    ->meta('author', 'Fayyaz Travels')
    ->meta('robots', 'index, follow')
    ->image($url . '/og/index.php?title=' . urlencode($pageName) . '&subtitle=' . urlencode($pageDescription) . '&gradient=' . urlencode('citrus_breeze'))
    ->canonical('https://fayyaztravels.com/visa/pages/' . $pageSlug);

// Convert both to strings and concatenate
$seo = $schema . "\n" . $webPageSchema . "\n" . $breadcrumbSchema . "\n" . $metatags;
// SEO END

// Output HTML head and scripts
echo html_head($pageName, null, true, [], false, false, $seo);
?>

<!-- Navbar -->
<?php require isset($_SESSION['user_id']) ? 'components/LoggedinNavbar.php' : 'components/Navbar.php'; ?>
<!-- ./Navbar -->

<!-- Page Content section -->
<article class="container mt-2">
    <div class="row">
        <div class="col-12">
            <h1>
                <b>
                    <?= htmlspecialchars($pageName, ENT_QUOTES, 'UTF-8'); ?>
                </b>
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/visa/" class="text-golden text-decoration-none"><i class="bi bi-house"></i> Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $pageName; ?></li>
                </ol>
            </nav>
            <?php
            $pageDetails = preg_replace_callback('/<a\s+([^>]*?)>/i', function ($matches) {
                // Check if the anchor tag already has a class attribute
                if (strpos($matches[1], 'class=') !== false) {
                    // Append to existing class
                    return '<a ' . preg_replace('/class=["\']([^"\']*)["\']/i', 'class="$1 text-golden"', $matches[1]) . '>';
                } else {
                    // Add new class attribute
                    return '<a class="text-golden" ' . $matches[1] . '>';
                }
            }, $pageDetails);

            ?>
            <?= $pageDetails; ?>

        </div>
    </div>
</article>

<!-- Footer -->
<?php require 'components/Footer.php'; ?>

<?php
echo html_scripts(
    includeJQuery: false,
    includeBootstrap: true,
    customScripts: [],
    includeSwal: false
);
?>

</body>

</html>