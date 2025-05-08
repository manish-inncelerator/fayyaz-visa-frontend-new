<?php
// Ensure decryptedId is set and valid
if (!isset($decryptedId) || empty($decryptedId)) {
  echo "Error: Decrypted ID is missing.";
  exit;
}

// Fetch travel document details
$travelDocument = $database->get('travel_documents', '*', [
  'traveler_id' => $decryptedId,
  'order_id' => $order_id
]) ?? [];

// Set default values
$passportType = getValue($travelDocument, 'passport_type', '');
$countryOfIssue = getValue($travelDocument, 'country_of_issue', '');
$placeOfIssue = getValue($travelDocument, 'place_of_issue', '');
$passportNumber = getValue($applicant, 'passport_number', '');
$issueDate = getValue($travelDocument, 'issue_date', '');
$expiryDate = getValue($travelDocument, 'expiry_date', '');
?>

<div class="card mb-3">
  <div class="card-header">
    <h5 class="mb-0">
      <i class="bi bi-passport me-2"></i> Travel Document Information
    </h5>
  </div>
  <div class="card-body">
    <div class="row g-3">
      <!-- Passport Type -->
      <div class="col-md-6">
        <label class="form-label">Type of Passport</label>
        <select name="passportType" class="form-select" required onchange="showPassportDescription();">
          <option value="" disabled <?= !$passportType ? 'selected' : ''; ?>>Please Choose</option>
          <?php
          $passportTypes = ["Ordinary", "Diplomatic", "Service", "Official", "Special", "Temporary", "Nuclear"];
          foreach ($passportTypes as $type) {
            echo "<option value='$type' " . ($passportType === $type ? 'selected' : '') . ">$type</option>";
          }
          ?>
        </select>
        <div class="valid-feedback">Looks good!</div>
        <div class="invalid-feedback">Please select a passport type.</div>

        <!-- Passport Type Description -->
        <div id="passportDescription" class="col-12 mt-2 small text-muted">
          <!-- Dynamic content injected here -->
        </div>
      </div>

      <!-- Country of Issue -->
      <div class="col-md-6">
        <label class="form-label">Country of Issue</label>
        <select name="countryOfIssue" class="form-select" required>
          <option value="" disabled <?= !$countryOfIssue ? 'selected' : ''; ?>>Please Choose</option>
          <!-- Dynamic options loaded via JSON -->
        </select>
        <div class="valid-feedback">Looks good!</div>
        <div class="invalid-feedback">Please select the country of issue.</div>
      </div>

      <!-- Place of Issue -->
      <div class="col-12">
        <label class="form-label">Place of Issue</label>
        <input type="text" name="placeOfIssue" class="form-control"
          value="<?= htmlspecialchars($placeOfIssue); ?>" required />
        <div class="valid-feedback">Looks good!</div>
        <div class="invalid-feedback">Please enter the place of issue.</div>
      </div>

      <!-- Passport Number -->
      <div class="col-md-4">
        <label class="form-label">Passport Number</label>
        <input type="text" name="passportNumber" class="form-control"
          value="<?= htmlspecialchars($passportNumber); ?>" required pattern="[A-Za-z0-9]{6,9}" />
        <div class="valid-feedback">Valid passport number!</div>
        <div class="invalid-feedback">
          Please enter a valid passport number (6-9 alphanumeric characters).
        </div>
      </div>

      <!-- Issue Date -->
      <div class="col-md-4">
        <label class="form-label">Issue Date</label>
        <input type="date" name="issueDate" class="form-control"
          value="<?= htmlspecialchars($issueDate); ?>" required />
        <div class="valid-feedback">Issue date is valid!</div>
        <div class="invalid-feedback">Please select an issue date.</div>
      </div>

      <!-- Expiry Date -->
      <div class="col-md-4">
        <label class="form-label">Expiry Date</label>
        <input type="date" name="expiryDate" class="form-control"
          value="<?= htmlspecialchars($expiryDate); ?>" required />
        <div class="valid-feedback">Expiry date is valid!</div>
        <div class="invalid-feedback">Please select an expiry date.</div>
      </div>

      <!-- Save Progress Button -->
      <div class="col-12">
        <input type="hidden" name="traveler_id" id="traveler_id" value="<?= htmlspecialchars($decryptedId); ?>" />
        <button type="button" class="btn btn-outline-golden" onclick="saveDraft(2); return false;">Save Progress</button>
      </div>
    </div>
  </div>
</div>