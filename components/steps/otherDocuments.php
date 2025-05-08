<?php

require_once 'vendor/autoload.php';

use Hashids\Hashids;


// Passport check
$passport_check = $database->get('passports', 'is_finished', ['order_id' => $order_id]);
if ($passport_check === 0 || $passport_check === null || $passport_check === '') {
    header('Location: passport');
    exit;
}

// Fetch travelers
$travelers = $database->select('travelers', ['name', 'id'], [
    'order_id' => $order_id,
    'ORDER' => ['id' => 'DESC']
]);

// Fetch country ID
$countryId = $database->get('orders', 'country_id', ['order_id' => $order_id]);

// Get required document IDs
$docIds = $database->get('visa_countries', 'documents', ['country_id' => $countryId]) ?? [];
$docIds = json_decode($docIds, true);
if (!is_array($docIds)) {
    die("Error: Failed to decode JSON.");
}
$docIdsArray = array_column($docIds, 'id');

// Map doc ID to type
$docTypeMap = [];
foreach ($docIds as $doc) {
    $docTypeMap[$doc['id']] = $doc['type'];
}

// Get document names - only if we have document IDs
$requiredDocs = [];
if (!empty($docIdsArray)) {
    $requiredDocs = $database->select('required_documents', ['id', 'required_document_name'], ['id' => $docIdsArray]);
}
?>

<?php foreach ($travelers as $traveler): ?>
    <div class="card mb-3">
        <div class="card-header fw-bold text-muted d-flex justify-content-between align-items-center">
            <div><i class="bi bi-upload"></i> Upload Documents</div>
            <div>
                <button class="btn btn-outline-secondary rounded-pill showQRCodeBtn d-none d-lg-block btn-sm text-decoration-none d-flex align-items-center" id="showQRCodeBtn">
                    <i class="bi bi-qr-code me-1"></i> Scan QR to upload snap using your phone
                </button>
            </div>
        </div>
        <div class="card-body">
            <h5 class="card-title text-center mb-3 fw-bold">
                Upload documents for <?= htmlspecialchars($traveler['name']) ?>
            </h5>

            <?php
            $missingDocs = [];

            foreach ($requiredDocs as $doc):
                $docType = $docTypeMap[$doc['id']] ?? 'optional';
                $is_req_file = ($docType === 'mandatory') ? 1 : 0;

                $typeBadge = $is_req_file
                    ? '<span class="badge bg-danger ms-2 float-end">Mandatory</span>'
                    : '<span class="badge bg-secondary ms-2 float-end">Optional</span>';


                $docKey = strtolower(preg_replace('/[^\w\s]/', ' ', $doc['required_document_name']));
                $uploadedDoc = $database->get('documents', ['document_filename'], [
                    'traveler_id' => $traveler['id'],
                    'order_id' => $order_id,
                    'document_type' => $docKey,
                    'is_finished' => 1
                ]);

                if ($docType === 'mandatory' && !$uploadedDoc) {
                    $missingDocs[] = $doc['id'];
                }
            ?>
                <div class="mb-4">
                    <label class="form-label"><b><?= htmlspecialchars($doc['required_document_name']) ?> <?= $typeBadge ?></b></label>

                    <?php if ($uploadedDoc): ?>
                        <div class="p-3 border rounded bg-white shadow-sm d-flex align-items-center justify-content-between uploadedDiv transition-all hover-shadow" data-traveler_id="<?= $traveler['id'] ?>" data-doc="<?= $doc['required_document_name'] ?>">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-file-earmark-check-fill text-success fs-4 me-3"></i>
                                <div>
                                    <h6 class="mb-0 text-golden fw-bold"><?= htmlspecialchars($doc['required_document_name']) ?></h6>
                                    <small class="text-muted">Successfully uploaded</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-success rounded-pill me-3 px-3 py-2">
                                    <i class="bi bi-check-circle-fill me-1"></i> Uploaded
                                </span>
                                <button class="btn btn-outline-danger btn-sm rounded-pill remove-upload"
                                    data-file="<?= $uploadedDoc['document_filename'] ?>"
                                    data-traveler-id="<?= $traveler['id'] ?>"
                                    data-traveler-name="<?= htmlspecialchars($traveler['name'], ENT_QUOTES, 'UTF-8') ?>"
                                    data-action="delete">
                                    <i class="bi bi-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="doc_uploader p-4 border border-dashed rounded-3 bg-light shadow-sm text-center transition-all hover-shadow"
                            id="doc_uploader-<?= $traveler['id'] ?>-<?= $doc['id'] ?>"
                            data-traveler_id="<?= $traveler['id'] ?>"
                            data-person_name="<?= $traveler['name'] ?>"
                            data-doc="<?= $doc['id'] ?>" data-required=="<?= $is_req_file ?>">
                            <i class="bi bi-cloud-arrow-up-fill text-primary fs-1 mb-2"></i>
                            <h6 class="mb-2">Drag & Drop files here</h6>
                            <p class="text-muted mb-2">or</p>
                            <button class="btn btn-primary btn-sm rounded-pill px-4 py-2 upload-click">
                                <i class="bi bi-file-earmark-arrow-up me-1"></i> Browse Files
                            </button>
                            <p class="text-muted small mt-2">Accepts PDF, JPG, PNG files</p>
                            <input type="file" accept=".pdf,image/*" multiple class="file-input" style="display: none;">
                        </div>
                        <div class="file-list mt-3" id="file-list-<?= $traveler['id'] ?>-<?= $doc['id'] ?>"></div>
                        <div id="uploadedDocuments"></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <!-- Mandatory check per traveler -->
            <input type="hidden" class="mandatory-check" data-traveler="<?= $traveler['id'] ?>" data-missing="<?= implode(',', $missingDocs) ?>">
        </div>
    </div>
<?php endforeach; ?>

<?php
// Step 1: Create a Hashids object with a salt and min length
$hashids = new Hashids('3Y1n?BuiI$5b(F{)I&J|8mXb', 16); // '6' = minimum length of encoded ID
$encryptedTravelerId = $hashids->encode($traveler['id']);
?>

<div class="d-flex justify-content-between align-items-center mt-3">
    <a href="application/<?= $order_id; ?>/passport" class="btn btn-golden btn-lg rounded-pill p-3 plexFont fw-bold fs-6">
        <i class="bi bi-chevron-left"></i> Back
    </a>
    <?php if ($countryName['country_name'] === 'Singapore'): ?>
        <a href="application/<?= $order_id; ?>/details?tid=<?= $encryptedTravelerId; ?>" type="submit" class="btn cta-button btn-disabled btn-lg rounded-pill p-3 plexFont fw-bold fs-6" id="saveNextBtnDocs" disabled>
            Save and Next <i class="bi bi-chevron-right"></i>
        </a>
    <?php else: ?>
        <a href="https://fayyaztravels.com/visa/payment/pay?order_id=<?= $order_id; ?>" type="submit" class="btn cta-button btn-disabled btn-lg rounded-pill p-3 plexFont fw-bold fs-6" id="saveNextBtnDocs" disabled>
            Save and Next <i class="bi bi-chevron-right"></i>
        </a>
    <?php endif; ?>
</div>

<!-- Upload overlay -->
<div id="uploadOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div class="text-white fw-bold fs-4">Uploading... Please wait...</div>
</div>