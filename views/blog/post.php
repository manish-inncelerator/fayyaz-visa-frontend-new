<?php
// views/blog/post.php

// Strict type checking and direct access prevention
declare(strict_types=1);
defined('BASE_DIR') || die('Direct access denied');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($params['slug']) ? e($params['slug']) : 'Blog Post' ?></title>
    <!-- Content Security Policy (Add your domains) -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'">
</head>

<body>
    <article class="blog-post">
        <?php if (isset($params['slug'])) : ?>
            <h1><?= e($params['slug']) ?></h1>
        <?php else : ?>
            <h1 class="error">Post Title Missing</h1>
        <?php endif; ?>

        <!-- Main content with fallback -->
        <div class="post-content">
            <?php if (isset($content)) : ?>
                <?= e($content) ?>
            <?php else : ?>
                <p class="warning">This post appears to be empty.</p>
            <?php endif; ?>
        </div>
    </article>

    <!-- Optional: Add CSRF token for forms -->
    <script type="application/json" id="csrf">
        <?= json_encode(['token' => $_SESSION['csrf_token'] ?? ''], JSON_HEX_TAG) ?>
    </script>
</body>

</html>