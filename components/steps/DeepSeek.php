<?php
/*
function encryptData($data, $key)
{
    return base64_encode(strrev(str_rot13($data . $key))); // Reverse + ROT13 + Base64
}

function decryptData($encryptedData, $key)
{
    return str_replace($key, '', str_rot13(strrev(base64_decode($encryptedData)))); // Decode + Reverse + ROT13
}

// Encryptiuon key for enc and dec
$encryptionKey = "72c440042ded5e0d0e36b5080fc3d696";

// $encryptedId = encryptData($travelers[0]['id'], $encryptionKey);

// User ID from the URL
$encryptedId = isset($_GET['tid']) ? trim(strip_tags(stripslashes($_GET['tid']))) : null;

// Decrypt Traveler ID
$decryptedId = decryptData($encryptedId, $encryptionKey);*/

require_once 'vendor/autoload.php';

use Hashids\Hashids;

$hashids = new Hashids('3Y1n?BuiI$5b(F{)I&J|8mXb', 16);

$encryptedId = isset($_GET['tid']) ? trim(strip_tags(stripslashes($_GET['tid']))) : null;

$decodedArray = $hashids->decode($encryptedId);
$decryptedId = isset($decodedArray[0]) ? (int)$decodedArray[0] : null;

// Robust validation
if (!is_int($decryptedId) || $decryptedId <= 0) {
    echo "Error: Decrypted ID is invalid or missing.";
    exit;
}
// Check if decryptedId is set and not empty
if (!isset($decryptedId) || empty($decryptedId)) {
    echo "Error: Decrypted ID is missing.";
    exit;
}

// Match decrypted ID to $t_id; fetch the traveler record
$t_id = $database->get('travelers', '*', [
    'id' => $decryptedId
]);

// // Check if a traveler was found
// if (!$t_id) {
//     header('Location: 404');
//     exit;
// }

// // If decryptedId doesn't match the database ID, redirect to 404
// if ($decryptedId != $t_id['id']) {
//     header('Location: 404');
//     exit;
// }
?>
<div class="card">
    <div class="card-header fw-bold text-muted d-flex justify-content-between align-items-center">
        <div>
            <i class="bi bi-pencil"></i> Add Details
        </div>
        <div>
            <button class="btn btn-outline-secondary rounded-pill showQRCodeBtn d-none d-lg-block btn-sm text-decoration-none d-flex align-items-center" id="showQRCodeBtn">
                <i class="bi bi-qr-code me-1"></i> Scan QR to fill this form using your phone
            </button>
        </div>
    </div>
    <div class="card-body">
        <!-- Form Section -->
        <form id="evisaForm" class="needs-validation" novalidate>
            <!-- Personal Information Section -->
            <?php include 'forms/singapore/personal_info.php'; ?>

            <!-- Travel Document Information Section -->
            <?php include 'forms/singapore/travel_document.php'; ?>

            <!-- Address Information Section -->
            <?php include 'forms/singapore/address_info.php'; ?>

            <!-- Occupation and Education Section -->
            <?php include 'forms/singapore/occupation_education.php'; ?>

            <!-- Information of Visit Section -->
            <?php include 'forms/singapore/visit_info.php'; ?>

            <!-- Additional Information Section -->
            <?php include 'forms/singapore/additional_info.php'; ?>

            <!-- Singapore Address Section -->
            <?php include 'forms/singapore/singapore_address.php'; ?>

            <!-- Antecedent Information Section -->
            <?php include 'forms/singapore/antecedent_info.php'; ?>

            <!-- Declaration and Terms Section -->
            <?php include 'forms/singapore/declaration_terms.php'; ?>

            <!-- Submit Button -->
            <div class="row mb-4 mt-4 mb-0">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <a href="application/<?= $order_id; ?>/docs" class="btn btn-golden btn-lg rounded-pill p-3 plexFont fw-bold fs-6">
                            <i class="bi bi-chevron-left"></i> Back
                        </a>
                        <button type="submit" class="btn cta-button btn-lg rounded-pill p-3 plexFont fw-bold fs-6">
                            Submit <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>