<?php
// Define the search condition (if $escapedQuery is set)
$searchCondition = [
    "visa_countries.is_active" => 1 // Ensure only active countries are fetched
];

// Add search filter if a query is provided
if (!empty($escapedQuery)) {
    $searchCondition["visa_countries.country_name[~]"] = "%{$escapedQuery}%";
}

// Fetch destinations from the database with images
try {
    // Fetch visa countries with related country images, visa types, and pricing
    $destinations = $database->select('visa_countries', [
        '[>]country_images' => ['country_id' => 'country_id'],
        '[>]visa_types'     => ['visa_type' => 'id'],
        '[>]visa_pricing'   => ['country_id' => 'country_id']
    ], [
        'visa_countries.id',
        'visa_countries.country_id(cid)',
        'visa_countries.country_name',
        'visa_countries.serviceability',
        'visa_countries.is_this_schengen',
        'visa_countries.fomo',
        'visa_types.visa_type(vtype)',
        'country_images.fallback_url',
        'country_images.photo_path',
        'visa_pricing.visa_type(vtypeee)',
    ], [
        'AND'   => $searchCondition,
        'GROUP' => 'visa_countries.id',  // one row per country
        'ORDER' => ['visa_countries.id' => 'DESC'],
        'LIMIT' => 15
    ]);
} catch (Exception $e) {
    $destinations = []; // Handle error and ensure the array is empty
}

?>

<?php if (empty($_GET['q'])): ?>
    <div class="text-center section-title">
        <h2 class="fw-bold mt-5">Explore Without Limits</h2>
        <p class="subtitle fs-5">Wherever you go, we've got the perfect visa for you</p>
    </div>
<?php endif; ?>


<div class="row">
    <?php foreach ($destinations as $destination):
        // Get all visa pricing rows for this country
        $pricingList = $database->select('visa_pricing', [
            'embassy_fee',
            'visa_assistance_fee',
            'vfs_service_fee',
            'single_entry_fee',
            'double_entry_fee',
            'multiple_entry_fee',
            'visa_on_arrival_fee',
            'visa_processing_time'
        ], [
            'country_id' => $destination['cid'],
            'ORDER' => ['id' => 'DESC']
        ]);

        // Calculate pricing and find cheapest option
        $lowestTotalFee = 0;
        $lowestProcessingTime = 0;
        $cheapestPricing = null;

        foreach ($pricingList as $pricing) {
            $embassy = (float) ($pricing['embassy_fee'] ?? 0);
            $assist = (float) ($pricing['visa_assistance_fee'] ?? 0);
            $vfs = (float) ($pricing['vfs_service_fee'] ?? 0);
            $single = (float) ($pricing['single_entry_fee'] ?? 0);
            $double = (float) ($pricing['double_entry_fee'] ?? 0);
            $multiple = (float) ($pricing['multiple_entry_fee'] ?? 0);
            $onArrival = (float) ($pricing['visa_on_arrival_fee'] ?? 0);

            $total = $embassy + $assist + $vfs + $single + $double + $multiple + $onArrival;

            if ($lowestTotalFee === 0 || $total < $lowestTotalFee) {
                $lowestTotalFee = $total;
                $lowestProcessingTime = (int) ($pricing['visa_processing_time'] ?? 0);
                $cheapestPricing = $pricing;
            }
        }

        // Prepare the data
        $photoPath = safeHtmlspecialchars($destination['photo_path'], 'assets/images/fly-high.webp');
        $countryName = safeHtmlspecialchars($destination['country_name']);
        $countrySlug = strtolower(str_replace(' ', '-', $countryName));

        // Calculate fees
        $visaAssistanceFee = (float) ($cheapestPricing['visa_assistance_fee'] ?? 0);
        $vfsServiceFees    = (float) ($cheapestPricing['vfs_service_fee'] ?? 0);
        $singleEntryFee    = (float) ($cheapestPricing['single_entry_fee'] ?? 0);
        $doubleEntryFee    = (float) ($cheapestPricing['double_entry_fee'] ?? 0);
        $multipleEntryFee  = (float) ($cheapestPricing['multiple_entry_fee'] ?? 0);
        $visaOnArrivalFee  = (float) ($cheapestPricing['visa_on_arrival_fee'] ?? 0);
        $embassyFee        = (float) ($cheapestPricing['embassy_fee'] ?? 0);

        $totalFee = $visaAssistanceFee + $vfsServiceFees + $embassyFee;
        $totalFee2 = $singleEntryFee + $doubleEntryFee + $multipleEntryFee + $visaOnArrivalFee + $totalFee;
        $finalDisplayPrice = $lowestTotalFee > 0 ? $lowestTotalFee : $totalFee2;

        $startingFromFormatted = number_format($finalDisplayPrice, 2, '.', '');
        $processingTime = (int) ($destination['visa_processing_time'] ?? $lowestProcessingTime);
        $basePath = ($photoPath === "assets/images/fly-high.webp") ? "" : "admin/";

        $hash = crc32($countryName);
        $issuedCount = 10 + ($hash % 50);

        // Get visa type with shorter display if needed
        if (!empty($destination['vtypeee'])) {
            $visaType = $database->get("visa_types", "visa_type", ["id" => $destination['vtypeee']]);
        } else {
            $visaType = safeHtmlspecialchars($destination['vtype'] ?? '');
        }
        if (strlen($visaType) > 12) {
            $visaType = substr($visaType, 0, 10) . '..';
        }

        // Format validity
        $validity = (int) ($cheapestPricing['visa_validity'] ?? 0);
        $formattedValidity = $validity >= 365 ? floor($validity / 365) . " years" : $validity . " days";
    ?>
        <div class="col-12 col-lg-4 col-xxl-4 mb-4">
            <div class="travel-card-3x7">
                <!-- Ribbon -->
                <div class="promo-tag-rbn">
                    <div class="promo-tag-inner-f4n">
                        <span class="promo-tag-label-z2j"><?= $visaType; ?></span>
                    </div>
                </div>

                <!-- Image Section -->
                <div class="media-wrapper-q9p">
                    <div class="media-gradient-p5v"></div>
                    <picture>
                        <source srcset="image.php?image=<?= $basePath . $photoPath; ?>&width=auto&height=300&quality=80&format=avif" type="image/avif">
                        <source srcset="image.php?image=<?= $basePath . $photoPath; ?>&width=auto&height=300&quality=80&format=webp">
                        <img loading="lazy"
                            src="image.php?image=<?= $basePath . $photoPath; ?>&width=auto&height=300&quality=70&format=jpeg"
                            class="destination-img-o7e"
                            alt="<?= $countryName; ?>">
                    </picture>

                    <!-- Country Name Overlay -->
                    <div class="location-title-box-l3h">
                        <h2 class="location-title-text-h8s"><?= $countryName; ?></h2>
                    </div>

                    <!-- Recent Issues Badge -->
                    <?php if ($issuedCount > 0): ?>
                        <div class="status-marker-j6r">
                            <div class="status-pill-k2d">
                                <div class="status-dot-v8n"></div>
                                <span class="status-label-m3c"><?= $issuedCount; ?> Issued Recently</span>
                                <?php if ($destination['is_this_schengen'] == 1): ?>
                                    <span class="status-label-m3c ms-1"> &bull; Schengen Country</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Card Content -->
                <div class="content-section-g5t">
                    <div class="fomo-wrapper" style="min-height: 24px;">
                        <?php if (!empty($destination['fomo'])): ?>
                            <p class="fw-bold mb-1">🔥<?= $destination['fomo']; ?></p>
                        <?php endif; ?>
                    </div>
                    <!-- Decorative Accent Line -->
                    <div class="divider-accent-i9w"></div>

                    <!-- Price and Processing Time -->
                    <div class="row align-items-center mb-3">
                        <div class="col-7">
                            <div class="fee-block-a7f">
                                <?php if ($singleEntryFee > 0 || $doubleEntryFee > 0 || $multipleEntryFee > 0 || $visaOnArrivalFee > 0): ?>
                                    <small class="fee-caption-y4e">STARTING FROM</small>
                                <?php endif; ?>
                                <div>

                                    <span class="fee-amount-w2j">S$<?= $startingFromFormatted; ?> <small>nett</small></span>
                                    <span class="fee-description-c6h mr-1"> (all inclusive)</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-5">
                            <div class="timeline-container-b5s">
                                <small class="timeline-caption-x7z">PROCESSING TIME</small>
                                <span class="timeline-value-n1p">
                                    <?= ($processingTime < 10 ? '⚡ ' : '') ?><?= $processingTime ?> Days
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Apply Button -->
                    <div class="action-wrapper-u3q">
                        <a href="country/apply-for-<?= $countrySlug; ?>-visa-online" class="btn btn-lg btn-blue d-block rounded-pill" style="transition: background-color 0.3s; text-decoration: none;" onmouseover="this.style.backgroundColor='var(--blue-dark)';" onmouseout="this.style.backgroundColor='';">
                            <span><b>Apply Now</b></span>
                            &nbsp;
                            <i class="bi bi-arrow-right-circle ml-2" style="transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.2)';" onmouseout="this.style.transform='scale(1)';"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php
// Helper function to safely handle null and pass to htmlspecialchars
function safeHtmlspecialchars($value, $default = '')
{
    return htmlspecialchars($value ?? $default);
}
?>