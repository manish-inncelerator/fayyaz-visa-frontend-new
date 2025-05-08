<?php
// Define steps
$steps = [
    [
        'step_number' => 1,
        'title' => 'Dates',
        'subtitle' => 'Journey Date',
        'status' => 'completed'
    ],
    [
        'step_number' => 2,
        'title' => 'Persona',
        'subtitle' => 'Travelers Profile',
        'status' => 'active'
    ],
    [
        'step_number' => 3,
        'title' => 'Photo',
        'subtitle' => 'Upload photo',
        'status' => 'pending'
    ],
    [
        'step_number' => 4,
        'title' => 'Passport',
        'subtitle' => 'Upload passport',
        'status' => 'pending'
    ],
    [
        'step_number' => 5,
        'title' => 'Other Documents',
        'subtitle' => 'Upload documents',
        'status' => 'pending'
    ],
];

// Conditionally add "Details" step if the country is Singapore
if ($countryName['country_name'] === 'Singapore') {
    $steps[] = [
        'step_number' => 6,
        'title' => 'Details',
        'subtitle' => 'Fill in the form',
        'status' => 'pending'
    ];
}

// Add "Checkout" step
$steps[] = [
    'step_number' => count($steps) + 1,
    'title' => 'Checkout',
    'subtitle' => 'Complete payment',
    'status' => 'final'
];

?>

<div class="stepper-container mb-3">
    <div class="stepper-wrapper">
        <?php foreach ($steps as $index => &$step):
            // Compare the lowercase title with currentStep to set the status
            $stepTitleLower = strtolower($step['title']);

            if ($currentStep === 'docs') {
                $currentStep = 'other documents'; // Ensure it matches the step title
            }

            if ($stepTitleLower === $currentStep) {
                $step['status'] = 'active'; // Mark current as active
            } elseif ($index < array_search($currentStep, array_map('strtolower', array_column($steps, 'title')))) {
                $step['status'] = 'completed'; // Mark previous as completed
            } else {
                $step['status'] = 'pending'; // Mark future steps as pending
            }

            // Determine text color class
            $textClass = ($step['status'] === 'completed') ? 'text-success' : (($step['status'] === 'active') ? 'text-dark' : 'text-muted');
        ?>
            <div class="step-item">
                <div class="step-content">
                    <div class="step-circle fw-bold <?= $step['status'] ?>">
                        <?= $step['status'] === 'completed' ? '<i class="bi bi-check-circle"></i>' : $step['step_number'] ?>
                    </div>
                    <div class="step-text">
                        <div class="step-title <?= $textClass ?>"><?= htmlspecialchars($step['title']) ?></div>
                        <div class="step-subtitle <?= $textClass ?>"><?= htmlspecialchars($step['subtitle']) ?></div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>