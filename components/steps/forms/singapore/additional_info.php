<?php
// Check if decryptedId is set and not empty
if (!isset($decryptedId) || empty($decryptedId)) {
    echo "Error: Decrypted ID is missing.";
    exit;
}

$additional_information = $database->get('additional_information', '*', [
    'traveler_id' => $decryptedId,
    'order_id' => $order_id
]);

?>

<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i> Additional Information</h5>
    </div>
    <div class="card-body">
        <div class="row g-3 needs-validation" novalidate>
            <!-- Travelling Alone -->
            <div class="col-12">
                <label class="form-label">Is the Applicant Travelling Alone?</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="radio" name="travellingAlone" value="yes" id="travellingAloneYes"
                        <?= isset($additional_information['travelling_alone']) && $additional_information['travelling_alone'] === 'yes' ? 'checked' : ''; ?> required>
                    <label class="form-check-label" for="travellingAloneYes">Yes</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="radio" name="travellingAlone" value="no" id="travellingAloneNo"
                        <?= isset($additional_information['travelling_alone']) && $additional_information['travelling_alone'] === 'no' ? 'checked' : ''; ?> required>
                    <label class="form-check-label" for="travellingAloneNo">No</label>
                </div>
                <div class="invalid-feedback">
                    Please select whether you are travelling alone or not.
                </div>
                <div class="valid-feedback">
                    Looks good!
                </div>
            </div>

            <!-- Relationship with Other Travelers -->
            <div class="col-12 <?= isset($additional_information['travelling_alone']) && $additional_information['travelling_alone'] === 'no' ? '' : 'd-none'; ?>"
                id="travelCompanionsGroup">
                <label class="form-label text-muted small">Please indicate applicant's relationship with other travelers and their passport numbers (i.e. in the format: Husband (V368221), Children (T429401, T389202) or Mother (X590307)). </label>
                <textarea name="travelCompanions" class="form-control" rows="3" id="travelCompanions"><?= safeHtmlspecialchars($additional_information['travel_companions'] ?? ''); ?></textarea>
                <div class="invalid-feedback">
                    Please provide details about your travel companions.
                </div>
                <div class="valid-feedback">
                    Travel companion details provided successfully.
                </div>
            </div>

            <div class="col-12">
                <input type="hidden" name="traveler_id" id="traveler_id" value="<?= safeHtmlspecialchars($decryptedId); ?>">
                <button type="button" class="btn btn-outline-golden" onclick="saveDraft(6); return false;">Save Progress</button>
            </div>
        </div>
    </div>
</div>