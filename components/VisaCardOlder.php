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
    $destinations = $database->select('visa_countries', [
        '[>]country_images' => ['country_id' => 'country_id'],
        '[>]visa_types' => ['visa_type' => 'id']
    ], [
        'visa_countries.id',
        'visa_countries.country_id(cid)',
        'visa_countries.country_name',
        'visa_countries.serviceability',
        'visa_countries.fomo',
        'visa_types.visa_type(vtype)',
        'country_images.fallback_url',
        'country_images.photo_path'
    ], array_merge($searchCondition, [
        "GROUP" => "visa_countries.id", // Ensures one result per visa listing
        "ORDER" => ["visa_countries.id" => "DESC"],
        "LIMIT" => 15
    ]));
} catch (Exception $e) {
    $destinations = []; // Ensure $destinations is an array to avoid issues in the foreach loop
}
?>

<div class="text-center section-title">
    <h2 class="fw-bold mt-5">Explore Without Limits</h2>
    <p class="subtitle fs-5">Wherever you go, we've got the perfect visa for you</p>
</div>
<div class="row">
    <?php foreach ($destinations as $destination):
        // Get all visa pricing rows for this country
        $pricingList = $database->select('visa_pricing', [
            'embassy_fee',
            'visa_assistance_fee',
            'vfs_service_fee',
            'single_entry_fee',
            'double_entry_fee', // Added double_entry_fee
            'multiple_entry_fee',
            'visa_on_arrival_fee',
            'visa_processing_time'
        ], [
            'country_id' => $destination['cid'],
            'ORDER' => ['id' => 'DESC']
        ]);

        // Default values
        $lowestTotalFee = 0;
        $lowestProcessingTime = 0;
        $cheapestPricing = null;

        // Find the lowest total fee from pricing list
        foreach ($pricingList as $pricing) {
            $embassy = (float) ($pricing['embassy_fee'] ?? 0);
            $assist = (float) ($pricing['visa_assistance_fee'] ?? 0);
            $vfs = (float) ($pricing['vfs_service_fee'] ?? 0);
            $single = (float) ($pricing['single_entry_fee'] ?? 0);
            $double = (float) ($pricing['double_entry_fee'] ?? 0); // Added double_entry_fee
            $multiple = (float) ($pricing['multiple_entry_fee'] ?? 0);
            $onArrival = (float) ($pricing['visa_on_arrival_fee'] ?? 0);

            $total = $embassy + $assist + $vfs + $single + $double + $multiple + $onArrival; // Updated total calculation

            if ($lowestTotalFee === 0 || $total < $lowestTotalFee) {
                $lowestTotalFee = $total;
                $lowestProcessingTime = (int) ($pricing['visa_processing_time'] ?? 0);
                $cheapestPricing = $pricing;
            }
        }

        // Apply safeHtmlspecialchars to handle null and prevent deprecation warning
        $photoPath = safeHtmlspecialchars($destination['photo_path'], 'assets/images/fly-high.webp');
        $countryName = safeHtmlspecialchars($destination['country_name']);
        $countrySlug = strtolower(str_replace(' ', '-', $countryName));

        // Ensuring all numeric values are safely cast from cheapest pricing
        $visaAssistanceFee = (float) ($cheapestPricing['visa_assistance_fee'] ?? 0);
        $vfsServiceFees    = (float) ($cheapestPricing['vfs_service_fee'] ?? 0);
        $singleEntryFee    = (float) ($cheapestPricing['single_entry_fee'] ?? 0);
        $doubleEntryFee    = (float) ($cheapestPricing['double_entry_fee'] ?? 0); // Added double_entry_fee
        $multipleEntryFee  = (float) ($cheapestPricing['multiple_entry_fee'] ?? 0);
        $visaOnArrivalFee  = (float) ($cheapestPricing['visa_on_arrival_fee'] ?? 0);
        $embassyFee        = (float) ($cheapestPricing['embassy_fee'] ?? 0);

        // Calculations
        $totalFee = $visaAssistanceFee + $vfsServiceFees + $embassyFee;
        $totalFee2 = $singleEntryFee + $doubleEntryFee + $multipleEntryFee + $visaOnArrivalFee + $totalFee; // Updated totalFee2 calculation

        // "Starting from" will use the lowest total from the list
        $finalDisplayPrice = $lowestTotalFee > 0 ? $lowestTotalFee : $totalFee2;

        // Formatting numbers correctly
        $embassyFeeFormatted        = number_format($embassyFee, 2, '.', '');
        $totalFeeFormatted          = number_format($totalFee, 2, '.', '');
        $totalFeeFormatted2         = number_format($totalFee2, 2, '.', '');
        $singleEntryFeeFormatted    = number_format($singleEntryFee, 2, '.', '');
        $doubleEntryFeeFormatted    = number_format($doubleEntryFee, 2, '.', ''); // Added formatting for double_entry_fee
        $multipleEntryFeeFormatted  = number_format($multipleEntryFee, 2, '.', '');
        $visaOnArrivalFeeFormatted  = number_format($visaOnArrivalFee, 2, '.', '');
        $startingFromFormatted      = number_format($finalDisplayPrice, 2, '.', '');

        $processingTime = (int) ($destination['visa_processing_time'] ?? $lowestProcessingTime); // fallback if needed
        $basePath = ($photoPath === "assets/images/fly-high.webp") ? "" : "admin/";

        // You can now use $startingFromFormatted to display "Starting from ₹xxx" or similar
    ?>

        <div class="col-12 col-lg-4 col-xxl-4 mb-3">
            <div class="visa-card mx-auto mb-3 h-100 d-flex flex-column rounded-3 overflow-hidden" style="border: 1px solid #e0e0e0; transition: transform 0.3s;">
                <div class="position-relative">
                    <picture>
                        <source srcset="image.php?image=<?= $basePath . $photoPath; ?>&width=auto&height=500&quality=80&format=avif" type="image/avif">
                        <source srcset="image.php?image=<?= $basePath . $photoPath; ?>&width=auto&height=500&quality=80&format=webp">
                        <img loading="lazy"
                            src="image.php?image=<?= $basePath . $photoPath; ?>&width=auto&height=500&quality=80&format=jpeg"
                            class="card-img-top img-fluid"
                            alt="<?= $countryName; ?>"
                            style="object-fit: cover; width: 100%; height: 280px;">
                    </picture>
                    <?php
                    // Generate a pseudo-random but consistent number per country
                    $hash = crc32($countryName); // get a numeric hash of the country name
                    $issuedCount = 10 + ($hash % 50); // gives you a number between 10-29
                    ?>
                    <span class="notification-badge position-absolute top-0 start-0 m-3 badge bg-golden" style="display: inline-block;">
                        <?= $issuedCount; ?> Issued Recently
                    </span>

                </div>

                <div class="card-body p-4 d-flex flex-column flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h2 class="h5 mb-0 fw-bold" style="color: var(--blue);"><?= $countryName; ?></h2>
                        <span class="visa-type-badge badge rounded-pill p-2" style="background-color: var(--accent); color: white;"><?= safeHtmlspecialchars($destination['vtype']); ?></span>
                    </div>
                    <?php if ($destination['fomo']): ?>
                        <!-- <p class="mb-0 text-danger fw-bold"></?= safeHtmlspecialchars($destination['fomo']); ?></p> -->
                    <?php endif; ?>

                    <hr class="card-divider mb-2" style="border-color: var(--blue-light);">


                    <div class="d-flex justify-content-center align-items-end mb-2 flex-grow-1"> <!-- Reduced mb-3 to mb-2 -->

                        <div class="text-center">
                            <?php if ($singleEntryFee > 0 || $doubleEntryFee > 0 || $multipleEntryFee > 0 || $visaOnArrivalFee > 0): ?>
                                <p class="text-muted mb-0">Starting from</p>
                                <div class="price-display fw-bold plexFont" style="color: var(--blue-dark);">
                                    S$<?= $totalFeeFormatted2; ?>
                                    <span class="h6 mb-0">nett</span> <!-- Reduced mb-2 to mb-0 -->
                                </div>
                                <small class="text-muted">Inclusive of visa and service fee</small>
                            <?php else: ?>
                                <div class="price-display fw-bold plexFont" style="color: var(--blue-dark);">
                                    S$<?= $totalFeeFormatted; ?>
                                    <span class="h6 mb-0">nett</span> <!-- Reduced mb-2 to mb-0 -->
                                </div>
                                <small class="text-muted">Inclusive of visa and service fee</small>
                            <?php endif; ?>
                        </div>

                        <div class="text-end"></div>

                    </div>

                    <a href="country/apply-for-<?= $countrySlug; ?>-visa-online" class="btn cta-button mt-3 p-3 d-grid btn-lg rounded-pill fw-bold mt-auto mb-0" style="background-color: var(--accent); color: white;">Apply Now</a>
                </div>
                <hr class="card-divider mb-2" style="border-color: var(--blue-light);">
                <div class="processing-time mb-2 alterFont small text-center py-1" style="color: var(--blue-light);">
                    Processing Time :
                    <span class="fw-bold"><?= ($processingTime < 10 && ($processingUnit ?? 'days') !== 'weeks' ? '⚡' : '') . $processingTime . " Days"; ?></span>
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