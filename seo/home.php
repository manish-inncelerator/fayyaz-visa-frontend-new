<?php
require_once 'vendor/autoload.php';

use Melbahja\Seo\Schema;
use Melbahja\Seo\Schema\Thing;

$schema = new Schema(
    new Thing('Organization', [
        'url'          => 'https://example.com',
        'logo'         => 'https://example.com/logo.png',
        'contactPoint' => new Thing('ContactPoint', [
            'telephone' => '+1-000-555-1212',
            'contactType' => 'customer service'
        ])
    ])
);

echo $schema;
