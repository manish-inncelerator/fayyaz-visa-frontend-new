<?php
$people = $database->select('travelers', ['name', 'id'], [
    'order_id' => $order_id
]);

$passports = $database->select('passports', '*', [
    'order_id' => $order_id,
    'is_finished' => 1
]);


// check photo table if is_finished = 1 then show passport upload page else redirect to photo page
$photos_check = $database->get('photos', 'is_finished', [
    'order_id' => $order_id
]);

if ($photos_check === 0 || $photos_check === null || $photos_check === '') {
    header('Location: photo');
    exit;
}


// Organize passports by traveler_id for quick lookup
$passportMap = [];
foreach ($passports as $passport) {
    $passportMap[$passport['traveler_id']] = $passport['passport_filename']; // Adjust column name as per your DB structure
}
?>
<div class="card mx-auto mb-2">
    <div class="card-header fw-bold text-muted d-flex justify-content-between align-items-center">
        <div>
            <i class="bi bi-upload"></i> Upload Passport
        </div>
        <div>
            <button class="btn btn-outline-secondary rounded-pill showQRCodeBtn d-none d-lg-block btn-sm text-decoration-none d-flex align-items-center" id="showQRCodeBtn">
                <i class="bi bi-qr-code me-1"></i> Scan QR to upload snap using your phone
            </button>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <?php foreach ($people as $index => $person):
                $passportExists = isset($passportMap[$person['id']]);
                $passportPath = $passportExists ? 'user_uploads/' . $order_id . '/' . preg_replace('/[^a-zA-Z0-9-_]/', '', str_replace(' ', '-', $person['name'])) . '/passport/' . $passportMap[$person['id']] : '';
            ?>

                <div class="col-12 col-lg-6 col-xxl-6 mb-2">
                    <div class="card w-100 passportCamContainer border-0">
                        <div class="card-body p-3">
                            <h5 class="card-title text-center mb-3 fw-bold">
                                <?= $passportExists ? 'Uploaded' : 'Upload' ?> <?= $person['name']; ?>'s Passport
                            </h5>

                            <div class="passport-upload-container border border-2 rounded-3 p-4 text-center position-relative square-upload d-flex align-items-center justify-content-center"
                                style="border-style: dashed; cursor: pointer; width: 100%; height: 300px;"
                                onclick="<?= $passportExists ? '' : "triggerFileInput($index)" ?>"
                                ondrop="handleDrop(event, <?= $index ?>)"
                                ondragover="event.preventDefault()">

                                <input type="file"
                                    name="traveler_passport_<?= $index ?>"
                                    id="passportInput_<?= $index ?>"
                                    class="form-control d-none"
                                    accept="image/jpeg, image/jpg, image/png, image/webp"
                                    data-person-name="<?= $person['name']; ?>"
                                    data-traveler-id="<?= $person['id']; ?>"
                                    onchange="validateVisapassport(this)">

                                <img id="passportUploadPreview_<?= $index ?>" class="img-fluid passport-upload-preview position-absolute top-0 start-0 w-100 h-100 object-fit-cover rounded-3 d-none" src="<?= $passportPath ?>" alt="<?= $person['name'] ?>'s passport">

                                <?php if ($passportExists): ?>
                                    <img id="passportPreview_<?= $index ?>" class="img-fluid position-absolute top-0 start-0 w-100 h-100 object-fit-cover rounded-3" src="<?= $passportPath ?>" alt="<?= $person['name'] ?>'s passport">

                                    <div class="position-absolute top-0 end-0 m-2">
                                        <button class="btn btn-danger btn-sm" onclick="deletepassport(<?= $person['id'] ?>, <?= $index ?>)">
                                            <i class="bi bi-trash"></i> Remove
                                        </button>
                                    </div>

                                <?php else: ?>
                                    <div id="passportPlaceholder_<?= $index ?>" class="w-100 h-100 d-flex flex-column justify-content-center align-items-center">
                                        <p class="text-muted mb-2">Click or drag & drop the passport here</p>
                                        <i class="bi bi-cloud-arrow-up display-4 text-secondary"></i>
                                    </div>
                                <?php endif; ?>

                                <div id="passportFeedback_<?= $index ?>" class="text-danger mt-2 position-absolute bottom-0 start-0 w-100 text-center"></div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-2">
            <a href="application/<?= $order_id; ?>/photo" class="btn btn-golden btn-lg rounded-pill p-3 plexFont fw-bold fs-6">
                <i class="bi bi-chevron-left"></i> Back
            </a>

            <a href="application/<?= $order_id; ?>/docs" type="submit" class="btn cta-button btn-disabled btn-lg rounded-pill p-3 plexFont fw-bold fs-6" id="saveNextBtnDocs" disabled>
                Save and Next <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>
</div>

<div id="uploadOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div class="text-white fw-bold fs-4">Uploading... Please wait...</div>
</div>