<?php
// Ensure decryptedId is set and valid
if (!isset($decryptedId) || empty($decryptedId)) {
  echo "Error: Decrypted ID is missing.";
  exit;
}

// Fetch address details from the database
$addresses = $database->get('addresses', '*', [
  'traveler_id' => $decryptedId,
  'order_id' => $order_id
]) ?? [];

// Set default values for address fields
$countryOfOrigin = getValue($addresses, 'country_of_origin');
$permanentAddress = getValue($addresses, 'permanent_address');
?>

<div class="card mb-3">
  <div class="card-header">
    <h5 class="mb-0"><i class="bi bi-house me-2"></i> Address Information</h5>
  </div>
  <div class="card-body">
    <div class="row g-3">
      <!-- Country of Origin -->
      <div class="col-md-12">
        <label for="countryOfOrigin" class="form-label">Country of Origin</label>
        <select
          id="countryOfOrigin"
          name="countryOfOrigin"
          class="form-select"
          required>
          <option value="">Select Country</option>
          <?php if (!empty($countryOfOrigin)): ?>
            <option value="<?= htmlspecialchars($countryOfOrigin); ?>" selected>
              <?= htmlspecialchars($countryOfOrigin); ?>
            </option>
          <?php endif; ?>
        </select>
        <div class="valid-feedback">Looks good!</div>
        <div class="invalid-feedback">Please select your country.</div>
      </div>

      <!-- Permanent Address -->
      <div class="col-12">
        <label for="permanentAddress" class="form-label">Permanent Address in Hometown</label>
        <textarea
          id="permanentAddress"
          name="permanentAddress"
          class="form-control"
          rows="3"
          required><?= htmlspecialchars(!empty($permanentAddress) ? $permanentAddress : ''); ?></textarea>
        <div class="valid-feedback">Looks good!</div>
        <div class="invalid-feedback">Please enter your permanent address.</div>
      </div>

      <!-- Hidden Traveler ID -->
      <div class="col-12">
        <input type="hidden" name="traveler_id" id="traveler_id" value="<?= htmlspecialchars($decryptedId); ?>" />
        <button type="button" class="btn btn-outline-golden" onclick="saveDraft(3); return false;">Save Progress</button>
      </div>
    </div>
  </div>
</div>