<?php


// check persona table if is_finished = 1 then show photo upload page else redirect to persona page
$passport_check = $database->get('passports', 'is_finished', [
    'order_id' => $order_id
]);

if ($passport_check === 0 || $passport_check === null || $passport_check === '') {
    header('Location: passport');
    exit;
}


// Fetch travelers for the given order
$travelers = $database->select('travelers', ['name', 'id'], ['order_id' => $order_id]);

// Fetch country ID for the order
$countryId = $database->get('orders', 'country_id', ['order_id' => $order_id]);

// Get required document IDs for the country
$docIds = $database->get('visa_countries', 'documents', ['country_id' => $countryId]) ?? [];

// Decode JSON string into an associative array
$docIds = json_decode($docIds, true);

if (!is_array($docIds)) {
    die("Error: Failed to decode JSON.");
}


// Extract only the IDs from $docIds
$docIdsArray = array_column($docIds, 'id');

// Fetch document names based on required document IDs
$requiredDocs = $database->select('required_documents', ['id', 'required_document_name'], ['id' => $docIdsArray]);
// $requiredDocs = $database->select('required_documents', ['id', 'required_document_name'], ['id' => $docIds]);


?>

<?php
function encryptData($data, $key)
{
    return base64_encode(strrev(str_rot13($data . $key))); // Reverse + ROT13 + Base64
}
$encryptionKey = "72c440042ded5e0d0e36b5080fc3d696";
?>


<?php foreach ($travelers as $traveler): ?>
    <div class="card mb-3">
        <div class="card-header fw-bold text-muted d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-upload"></i> Upload Documents
            </div>
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

            <?php foreach ($requiredDocs as $doc): ?>
                <?php
                // Fetch uploaded document
                $uploadedDoc = $database->get('documents', ['document_filename'], [
                    'traveler_id' => $traveler['id'],
                    'order_id' => $order_id,
                    'document_type' => strtolower(preg_replace('/[^\w\s]/', ' ', $doc['required_document_name'])),
                    'is_finished' => 1
                ]);
                ?>
                <div class="mb-4">
                    <label class="form-label"><b><?= htmlspecialchars($doc['required_document_name']) ?></b></label>

                    <?php if ($uploadedDoc): ?>
                        <div class="p-3 border rounded bg-light shadow-sm d-flex align-items-center justify-content-between uploadedDiv">
                            <b class="text-golden"> <?= htmlspecialchars($doc['required_document_name']) ?> </b>
                            <span class="text-success">Uploaded ✅</span>
                            <button class="btn btn-danger btn-sm remove-upload"
                                data-file="<?= $uploadedDoc['document_filename'] ?>"
                                data-traveler-id="<?= $traveler['id'] ?>"
                                data-traveler-name="<?= htmlspecialchars($traveler['name'], ENT_QUOTES, 'UTF-8') ?>"
                                onclick="handleDelete(this, '<?= $uploadedDoc['document_filename'] ?>', '<?= $traveler['id'] ?>', '<?= htmlspecialchars($traveler['name'], ENT_QUOTES, 'UTF-8') ?>')">
                                <i class="bi bi-trash"></i> Remove
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="doc_uploader p-3 border rounded shadow-sm text-center"
                            id="doc_uploader-<?= $traveler['id'] ?>-<?= $doc['id'] ?>"
                            data-traveler_id="<?= $traveler['id'] ?>"
                            data-person_name="<?= $traveler['name'] ?>"
                            data-doc="<?= $doc['id'] ?>">
                            <span class="upload-text upload-click">Drag & Drop files here or <span class="text-primary">Click to Upload</span></span>
                            <input type="file" accept=".pdf,image/*" multiple class="file-input" style="display: none;">
                        </div>

                        <div class="file-list mt-2" id="file-list-<?= $traveler['id'] ?>-<?= $doc['id'] ?>"></div>
                        <div id="uploadedDocuments"></div>
                    <?php endif; ?>


                </div>
            <?php endforeach; ?>

        </div>
    </div>
<?php endforeach; ?>
<?php
// Encrypt Traveler ID
$encryptedId = encryptData($travelers[0]['id'], $encryptionKey);
?>
<div class="d-flex justify-content-between align-items-center mt-3">
    <a href="application/<?= $order_id; ?>/passport" class="btn btn-golden btn-lg rounded-pill p-3 plexFont fw-bold fs-6">
        <i class="bi bi-chevron-left"></i> Back
    </a>
    <?php if ($countryName === 'Singapore'): ?>
        <a href="application/<?= $order_id; ?>/details?tid=<?= $encryptedId; ?>" type="submit" class="btn cta-button btn-disabled btn-lg rounded-pill p-3 plexFont fw-bold fs-6" id="saveNextBtnDocs" disabled>
            Save and Next <i class="bi bi-chevron-right"></i>
        </a>
    <?php else: ?>
        <a href="application/<?= $order_id; ?>/checkout" type="submit" class="btn cta-button btn-disabled btn-lg rounded-pill p-3 plexFont fw-bold fs-6" id="saveNextBtnDocs" disabled>
            Save and Next <i class="bi bi-chevron-right"></i>
        </a>
    <?php endif; ?>
</div>



<div id="uploadOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div class="text-white fw-bold fs-4">Uploading... Please wait...</div>
</div>