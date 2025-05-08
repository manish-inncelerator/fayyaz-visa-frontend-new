<?php
if (!isset($decryptedId) || empty($decryptedId)) {
  echo "Error: Decrypted ID is missing.";
  exit;
}

// Fetch applicant data
$applicant = $database->get('travelers', '*', [
  'id' => $decryptedId,
  'order_id' => $order_id
]) ?? [];

$applicantData = $database->get('applicants', '*', [
  'traveler_id' => $decryptedId,
  'order_id' => $order_id
]) ?? [];

// Helper function for safe value retrieval
function getValue($array, $key, $default = null)
{
  return $array[$key] ?? $default;
}

?>

<div class="card mb-2">
  <div class="card-header">
    <h5 class="mb-0">
      <i class="bi bi-person-circle me-2"></i> Personal Information
    </h5>
  </div>
  <div class="card-body">
    <div class="row g-3">
      <!-- Full Name -->
      <div class="col-12">
        <label class="form-label" for="fullName">Full Name (as shown in passport)</label>
        <input type="text" name="fullName" id="fullName" class="form-control"
          placeholder="Enter your full name"
          value="<?= htmlspecialchars(getValue($applicant, 'name')) ?>"
          required />
        <div class="invalid-feedback">Please enter your full name as shown in passport.</div>
        <div class="valid-feedback">Looks good!</div>
      </div>

      <!-- Date of Birth -->
      <div class="col-md-6">
        <label class="form-label" for="dob">Date of Birth</label>
        <input type="date" name="dob" id="dob" class="form-control"
          value="<?= getValue($applicant, 'date_of_birth') ?>" required />
        <div class="invalid-feedback">Please select your date of birth.</div>
        <div class="valid-feedback">Looks good!</div>
      </div>

      <!-- Gender -->
      <div class="col-md-6">
        <label class="form-label">Gender</label>
        <div class="d-flex gap-3">
          <?php $gender = getValue($applicantData, 'gender', 'male'); ?>
          <input type="radio" name="gender" value="male" id="genderMale"
            <?= ($gender === 'male') ? 'checked' : '' ?> required />
          <label for="genderMale">Male</label>

          <input type="radio" name="gender" value="female" id="genderFemale"
            <?= ($gender === 'female') ? 'checked' : '' ?> required />
          <label for="genderFemale">Female</label>
        </div>
        <div class="invalid-feedback" id="genderFeedback">Please select your gender.</div>
        <div class="valid-feedback">Looks good!</div>
      </div>

      <!-- Race -->
      <div class="col-md-6">
        <label class="form-label" for="raceSelect">Race</label>
        <select name="raceSelect" id="raceSelect" class="form-select" required>
          <option value="" disabled selected>Select Race</option>
          <!-- Options loaded dynamically from JSON -->
        </select>
        <div class="invalid-feedback">Please select your race.</div>
        <div class="valid-feedback">Looks good!</div>
      </div>

      <!-- Country of Birth -->
      <div class="col-md-6">
        <label class="form-label" for="countrySelect">Country of Birth</label>
        <select name="countrySelect" id="countrySelect" class="form-select" required>
          <option value="" disabled selected>Select Country</option>
          <!-- Options loaded dynamically from JSON -->
        </select>
        <div class="invalid-feedback">Please select your country of birth.</div>
        <div class="valid-feedback">Looks good!</div>
      </div>

      <!-- Nationality -->
      <div class="col-md-4">
        <label class="form-label" for="nationalitySelect">Nationality</label>
        <select name="nationalitySelect" id="nationalitySelect" class="form-select" required>
          <option value="" disabled selected>Select Nationality</option>
          <!-- Options loaded dynamically from JSON -->
        </select>
        <div class="invalid-feedback">Please select your nationality.</div>
        <div class="valid-feedback">Looks good!</div>
      </div>

      <!-- Religion -->
      <div class="col-md-4">
        <label class="form-label" for="religionSelect">Religion</label>
        <select name="religionSelect" id="religionSelect" class="form-select" required>
          <option value="" disabled selected>Select Religion</option>
          <!-- Options loaded dynamically from JSON -->
        </select>
        <div class="invalid-feedback">Please select your religion.</div>
        <div class="valid-feedback">Looks good!</div>
      </div>

      <!-- Marital Status -->
      <div class="col-md-4">
        <label class="form-label" for="maritalStatus">Marital Status</label>
        <select name="maritalStatus" id="maritalStatus" class="form-select" required>
          <?php $maritalStatus = getValue($applicantData, 'marital_status'); ?>
          <option value="" disabled <?= !$maritalStatus ? 'selected' : '' ?>>Please Choose</option>
          <option value="Single" <?= ($maritalStatus === 'Single') ? 'selected' : '' ?>>Single</option>
          <option value="Married" <?= ($maritalStatus === 'Married') ? 'selected' : '' ?>>Married</option>
          <option value="Divorced" <?= ($maritalStatus === 'Divorced') ? 'selected' : '' ?>>Divorced</option>
          <option value="Widowed" <?= ($maritalStatus === 'Widowed') ? 'selected' : '' ?>>Widowed</option>
        </select>
        <div class="invalid-feedback">Please select your marital status.</div>
        <div class="valid-feedback">Looks good!</div>
      </div>

      <!-- Spouse Nationality (Conditional) -->
      <div class="col-12" id="spouseNationalityField" style="display: none">
        <label class="form-label" for="spouseNationality">Nationality of Spouse (If married)</label>
        <input type="text" name="spouseNationality" id="spouseNationality" class="form-control"
          placeholder="Enter spouse's nationality" />
        <div class="invalid-feedback">Please enter your spouse's nationality.</div>
        <div class="valid-feedback">Looks good!</div>
      </div>

      <!-- Save Progress Button -->
      <div class="col-12">
        <input type="hidden" name="traveler_id" id="traveler_id" value="<?= htmlspecialchars($decryptedId) ?>">
        <button type="button" class="btn btn-outline-golden" onclick="saveDraft(1); return false;">Save Progress</button>
      </div>
    </div>
  </div>
</div>