<?php
// Check if decryptedId is set and not empty
if (!isset($decryptedId) || empty($decryptedId)) {
    echo "Error: Decrypted ID is missing.";
    exit;
}

$addresses = $database->get('addresses', ['singapore_address', 'hotel_name'], [
    'traveler_id' => $decryptedId,
    'order_id' => $order_id
]);

// Fetch accommodation information from the database (if available)
$accommodation = $addresses['singapore_address'] ?? '';
$hotel_name = $addresses['hotel_name'] ?? '';

?>
<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i> Singapore Address</h5>
    </div>
    <div class="card-body">
        <div class="row g-3 needs-validation" novalidate>
            <!-- Accommodation Details -->
            <div class="col-12">
                <label class="form-label">Where will the applicant be staying?</label>
                <select name="accommodation" class="form-select" required id="accommodation">
                    <option value="" disabled <?= empty($accommodation) ? 'selected' : ''; ?>>Please Choose</option>
                    <option value="friends" <?= $accommodation === 'friends' ? 'selected' : ''; ?>>Friend's Place</option>
                    <option value="hotel" <?= $accommodation === 'hotel' ? 'selected' : ''; ?>>Hotel</option>
                    <option value="next_of_kin" <?= $accommodation === 'next_of_kin' ? 'selected' : ''; ?>>Next of Kin's Place</option>
                    <option value="relative" <?= $accommodation === 'relative' ? 'selected' : ''; ?>>Relative's Place</option>
                    <option value="others" <?= $accommodation === 'others' ? 'selected' : ''; ?>>Others</option>
                </select>
                <div class="invalid-feedback">
                    Please provide the accommodation details.
                </div>
                <div class="valid-feedback">
                    Looks good!
                </div>
            </div>

            <!-- Hotel/Building Name (Initially Hidden) -->
            <div class="col-12" id="hotelNameGroup" style="<?= !empty($hotel_name) ? 'display: block;' : 'display: none;'; ?>">
                <label class="form-label">Hotel / Building Name</label>
                <input type="text" name="hotelName" class="form-control" value="<?= safeHtmlspecialchars($hotel_name); ?>" <?= empty($hotel_name) ? '' : 'required'; ?>>
                <div class="invalid-feedback">
                    Please provide the hotel/building name.
                </div>
                <div class="valid-feedback">
                    Looks good!
                </div>
            </div>

            <div class="col-12">
                <input type="hidden" name="traveler_id" id="traveler_id" value="<?= safeHtmlspecialchars($decryptedId); ?>">
                <button type="button" class="btn btn-outline-golden" onclick="saveDraft(9); return false;">Save Progress</button>
            </div>
        </div>
    </div>
</div>