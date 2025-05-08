<?php
$people = $database->select('travelers', ['name', 'id'], [
    'order_id' => $order_id
]);

$photos = $database->select('photos', '*', [
    'order_id' => $order_id,
    'is_finished' => 1
]);

// check persona table if is_finished = 1 then show photo upload page else redirect to persona page
$persona_check = $database->get('travelers', 'is_finished', [
    'order_id' => $order_id
]);

if ($persona_check === 0 || $persona_check === null || $persona_check === '') {
    header('Location: persona');
    exit;
}

// Organize photos by traveler_id for quick lookup
$photoMap = [];
foreach ($photos as $photo) {
    $photoMap[$photo['traveler_id']] = $photo['photo_filename']; // Adjust column name as per your DB structure
}
// Save the traveler details to the details_checklist table
foreach ($people as $person) {
    // Check if the specific traveler already exists for this order
    $exists = $database->has("details_checklist", [
        "order_id" => $order_id,
        "traveler_id" => $person['id']
    ]);

    if (!$exists) {
        $database->insert("details_checklist", [
            "order_id" => $order_id,
            "traveler_id" => $person['id'],
            "is_finished" => 0
        ]);
    }
}

?>
<div class="card mx-auto mb-2">
    <div class="card-header fw-bold text-muted d-flex justify-content-between align-items-center">
        <div>
            <i class="bi bi-upload"></i> Upload Passport Photo
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
                $photoExists = isset($photoMap[$person['id']]);
                $photoPath = $photoExists ? 'user_uploads/' . $order_id . '/' . preg_replace('/[^a-zA-Z0-9-_]/', '', str_replace(' ', '-', $person['name'])) . '/' . $photoMap[$person['id']] : '';
            ?>

                <div class="col-12 col-lg-6 col-xxl-6 mb-2">
                    <div class="card w-100 photoCamContainer border-0">
                        <div class="card-body p-3">
                            <h5 class="card-title text-center mb-3 fw-bold">
                                <?= $photoExists ? 'Uploaded' : 'Upload' ?> <?= $person['name']; ?>'s Photo
                            </h5>

                            <div class="photo-upload-container border border-2 rounded-3 p-4 text-center position-relative square-upload d-flex align-items-center justify-content-center"
                                style="border-style: dashed; cursor: pointer; width: 100%; height: 300px;"
                                onclick="<?= $photoExists ? '' : "triggerFileInput($index)" ?>"
                                ondrop="handleDrop(event, <?= $index ?>)"
                                ondragover="event.preventDefault()">

                                <input type="file"
                                    name="traveler_photo_<?= $index ?>"
                                    id="photoInput_<?= $index ?>"
                                    class="form-control d-none"
                                    accept="image/jpeg, image/jpg, image/png, image/webp"
                                    data-person-name="<?= $person['name']; ?>"
                                    data-traveler-id="<?= $person['id']; ?>"
                                    onchange="validateVisaPhoto(this)">

                                <img id="photoUploadPreview_<?= $index ?>" class="img-fluid photo-upload-preview position-absolute top-0 start-0 w-100 h-100 object-fit-cover rounded-3 d-none" src="<?= $photoPath ?>" alt="<?= $person['name'] ?>'s Photo">

                                <?php if ($photoExists): ?>
                                    <img id="photoPreview_<?= $index ?>" class="img-fluid position-absolute top-0 start-0 w-100 h-100 object-fit-cover rounded-3" src="<?= $photoPath ?>" alt="<?= $person['name'] ?>'s Photo">

                                    <div class="position-absolute top-0 end-0 m-2">
                                        <button class="btn btn-danger btn-sm" onclick="deletePhoto(<?= $person['id'] ?>, <?= $index ?>)">
                                            <i class="bi bi-trash"></i> Remove
                                        </button>
                                    </div>

                                <?php else: ?>
                                    <div id="photoPlaceholder_<?= $index ?>" class="w-100 h-100 d-flex flex-column justify-content-center align-items-center">
                                        <p class="text-muted mb-2">Click or drag & drop the photo here</p>
                                        <i class="bi bi-cloud-arrow-up display-4 text-secondary"></i>
                                    </div>
                                <?php endif; ?>

                                <div id="photoFeedback_<?= $index ?>" class="text-danger mt-2 position-absolute bottom-0 start-0 w-100 text-center"></div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-2">
            <a href="application/<?= $order_id; ?>/persona" class="btn btn-golden btn-lg rounded-pill p-3 plexFont fw-bold fs-6">
                <i class="bi bi-chevron-left"></i> Back
            </a>
            <a href="application/<?= $order_id; ?>/passport" type="submit" class="btn cta-button btn-disabled btn-lg rounded-pill p-3 plexFont fw-bold fs-6" id="saveNextBtn" disabled>
                Save and Next <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>
</div>

<div id="uploadOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div class="text-white fw-bold fs-4">Uploading... Please wait... <span id="uploadComplete"></span></div>
</div>