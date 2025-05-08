<?php
// Check if decryptedId is set and not empty
if (!isset($decryptedId) || empty($decryptedId)) {
    echo "Error: Decrypted ID is missing.";
    exit;
}

$visit_information = $database->get('visit_information', '*', [
    'traveler_id' => $decryptedId,
    'order_id' => $order_id
]);


?>

<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-airplane me-2"></i> Information of Visit</h5>
    </div>
    <div class="card-body">
        <div class="row g-3 needs-validation" novalidate>
            <!-- Expected Date of Arrival -->
            <div class="col-md-6">
                <label class="form-label">Expected Date of Arrival</label>
                <input type="date" name="arrivalDate" class="form-control" value="<?= safeHtmlspecialchars($visit_information['arrival_date'] ?? ''); ?>" required>
                <div class="valid-feedback">Looks good!</div>
                <div class="invalid-feedback">Please select an arrival date.</div>
            </div>

            <!-- Inflight Number -->
            <div class="col-md-6">
                <label class="form-label">Inflight Number</label>
                <input type="text" name="inflightNumber" class="form-control" value="<?= safeHtmlspecialchars($visit_information['inflight_number'] ?? ''); ?>" required>
                <div class="valid-feedback">Looks good!</div>
                <div class="invalid-feedback">Please enter your inflight number.</div>
            </div>

            <!-- Type of Visa -->
            <div class="col-md-6">
                <label class="form-label">Type of Visa</label>
                <select name="visaType" class="form-select" required>
                    <option value="" disabled <?= empty($visit_information['visa_type']) ? 'selected' : ''; ?>>Please Choose</option>
                    <option value="Tourist" <?= ($visit_information['visa_type'] ?? '') === 'Tourist' ? 'selected' : ''; ?>>Tourist</option>
                    <option value="Business" <?= ($visit_information['visa_type'] ?? '') === 'Business' ? 'selected' : ''; ?>>Business</option>
                    <option value="Student" <?= ($visit_information['visa_type'] ?? '') === 'Student' ? 'selected' : ''; ?>>Student</option>
                    <option value="Work" <?= ($visit_information['visa_type'] ?? '') === 'Work' ? 'selected' : ''; ?>>Work</option>
                </select>
                <div class="valid-feedback">Looks good!</div>
                <div class="invalid-feedback">Please select a visa type.</div>
            </div>

            <!-- Intended Stay Duration -->
            <div class="col-md-6">
                <label class="form-label">Intended Stay Duration</label>
                <div class="input-group">
                    <input type="number" name="stayDuration" class="form-control" value="<?= safeHtmlspecialchars($visit_information['stay_duration'] ?? ''); ?>" required>
                    <span class="input-group-text">days</span>
                </div>
                <div class="valid-feedback">Looks good!</div>
                <div class="invalid-feedback">Please specify your stay duration.</div>
            </div>

            <!-- Date of Departure -->
            <div class="col-md-6">
                <label class="form-label">Date of Departure</label>
                <input type="date" name="departureDate" class="form-control" value="<?= safeHtmlspecialchars($visit_information['departure_date'] ?? ''); ?>" required>
                <div class="valid-feedback">Looks good!</div>
                <div class="invalid-feedback">Please select a departure date.</div>
            </div>

            <!-- Outflight Number -->
            <div class="col-md-6">
                <label class="form-label">Outflight Number</label>
                <input type="text" name="outflightNumber" class="form-control" value="<?= safeHtmlspecialchars($visit_information['outflight_number'] ?? ''); ?>" required>
                <div class="valid-feedback">Looks good!</div>
                <div class="invalid-feedback">Please enter your outflight number.</div>
            </div>

            <!-- Purpose of Visit -->
            <div class="col-12">
                <label class="form-label">Purpose of Visit</label>
                <select name="purposeOfVisit" class="form-select mb-2" id="purposeSelect" required>
                    <option value="" <?= empty($visit_information['purpose_of_visit']) ? 'selected' : ''; ?>>Choose a purpose</option>
                    <option value="Tourism" <?= ($visit_information['purpose_of_visit'] ?? '') === 'Tourism' ? 'selected' : ''; ?>>Tourism</option>
                    <option value="Business" <?= ($visit_information['purpose_of_visit'] ?? '') === 'Business' ? 'selected' : ''; ?>>Business</option>
                    <option value="Family Visit" <?= ($visit_information['purpose_of_visit'] ?? '') === 'Family Visit' ? 'selected' : ''; ?>>Family Visit</option>
                    <option value="other" <?= ($visit_information['purpose_of_visit'] ?? '') === 'other' ? 'selected' : ''; ?>>Other</option>
                </select>
                <div class="valid-feedback">Looks good!</div>
                <div class="invalid-feedback">Please select the purpose of your visit.</div>

                <input type="text" name="otherPurpose" id="otherPurposeField" class="form-control mt-2 <?= ($visit_information['purpose_of_visit'] ?? '') === 'other' ? '' : 'd-none'; ?>" value="<?= safeHtmlspecialchars($visit_information['other_purpose'] ?? ''); ?>" placeholder="If Other, please specify">
            </div>

            <div class="col-12">
                <input type="hidden" name="traveler_id" id="traveler_id" value="<?= safeHtmlspecialchars($decryptedId); ?>">
                <button type="button" class="btn btn-outline-golden" onclick="saveDraft(5); return false;">Save Progress</button>
            </div>
        </div>
    </div>
</div>