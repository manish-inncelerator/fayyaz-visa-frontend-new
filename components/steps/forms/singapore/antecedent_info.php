<?php
if (!isset($decryptedId) || empty($decryptedId)) {
    echo "Error: Decrypted ID is missing.";
    exit;
}

// Fetch antecedent information
$antecedent_information = $database->get('antecedent_information', '*', [
    'traveler_id' => $decryptedId,
    'order_id' => $order_id
]);

// Check if $antecedent_information is null or not
if ($antecedent_information === null) {
    $antecedent_information = [];
}

?>
<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i> Antecedent Information</h5>
    </div>
    <div class="card-body">
        <div class="mb-2">
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Please fill this section carefully.
            </div>
        </div>
        <div class="row g-3 needs-validation" novalidate>
            <!-- Resided in Other Countries -->
            <div class="col-12">
                <label class="form-label">
                    Has the applicant ever resided in countries other than the country of origin for 1 year or more in the last 5 years? If Yes, Please provide (Country, Address, Period of stay)
                    <span class="important-field">*IMPORTANT</span>
                </label>
                <textarea name="residedAbroad" class="form-control" rows="3" required><?php echo safeHtmlspecialchars($antecedent_information['resided_abroad'] ?? ''); ?></textarea>
                <div class="invalid-feedback">
                    Please provide the details of countries where the applicant has resided.
                </div>
                <div class="valid-feedback">
                    Looks good!
                </div>
            </div>

            <!-- Refused Entry or Deported -->
            <div class="col-12">
                <label class="form-label">Has the applicant ever been refused entry into or deported from any country, including Singapore?</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="refusedEntry" value="yes" id="refusedEntrySwitch" <?php echo (isset($antecedent_information['refused_entry']) && $antecedent_information['refused_entry'] === 'yes') ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="refusedEntrySwitch">Yes</label>
                    <div class="invalid-feedback">
                        Please select if the applicant has ever been refused entry or deported.
                    </div>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                </div>
            </div>

            <!-- Convicted in Court -->
            <div class="col-12">
                <label class="form-label">Has the applicant ever been convicted in a court of law in any country, including Singapore?</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="convicted" value="yes" id="convictedSwitch" <?php echo (isset($antecedent_information['convicted']) && $antecedent_information['convicted'] === 'yes') ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="convictedSwitch">Yes</label>
                    <div class="invalid-feedback">
                        Please select if the applicant has ever been convicted in a court of law.
                    </div>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                </div>
            </div>

            <!-- Prohibited from Entering Singapore -->
            <div class="col-12">
                <label class="form-label">Has the applicant ever been prohibited from entering Singapore?</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="prohibitedEntry" value="yes" id="prohibitedEntrySwitch" <?php echo (isset($antecedent_information['prohibited']) && $antecedent_information['prohibited'] === 'yes') ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="prohibitedEntrySwitch">Yes</label>
                    <div class="invalid-feedback">
                        Please select if the applicant has ever been prohibited from entering Singapore.
                    </div>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                </div>
            </div>

            <!-- Travelled with Different Passport or Name -->
            <div class="col-12">
                <label class="form-label">Has the applicant ever entered Singapore using a different passport or name?</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="differentPassport" value="yes" id="differentPassportSwitch" <?php echo (isset($antecedent_information['different_passport']) && $antecedent_information['different_passport'] === 'yes') ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="differentPassportSwitch">Yes</label>
                    <div class="invalid-feedback">
                        Please select if the applicant has ever entered Singapore using a different passport or name.
                    </div>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                </div>
            </div>

            <!-- Additional Details (Initially hidden) -->
            <div class="col-12" id="additionalDetailsContainer" style="display: none;">
                <label class="form-label">If any of the answer is 'Yes', please furnish details below:</label>
                <textarea name="additionalDetails" class="form-control" rows="3"><?php echo safeHtmlspecialchars($antecedent_information['antecedent_details'] ?? ''); ?></textarea>
                <div class="invalid-feedback">
                    Please provide additional details if applicable.
                </div>
                <div class="valid-feedback">
                    Looks good!
                </div>
            </div>

            <!-- Travelling From -->
            <div class="col-12">
                <label class="form-label">
                    Which country is the applicant travelling from before arriving to Singapore?
                    <span class="important-field">*IMPORTANT</span>
                </label>
                <select name="travellingFrom" id="travellingFrom" class="form-select" required>
                    <option value="" disabled selected>Select a country</option>
                    <?php
                    // Populate the select dropdown with countries
                    foreach ($countries as $country) {
                        $selected = (isset($antecedent_information['travelling_from_country']) && $antecedent_information['travelling_from_country'] === $country['name']) ? 'selected' : '';
                        echo "<option value='{$country['name']}' $selected>{$country['name']}</option>";
                    }
                    ?>
                </select>
                <div class="invalid-feedback">
                    Please select the country the applicant is traveling from.
                </div>
                <div class="valid-feedback">
                    Looks good!
                </div>
            </div>

            <div class="col-12">
                <input type="hidden" name="traveler_id" id="traveler_id" value="<?= safeHtmlspecialchars($decryptedId); ?>">
                <button type="button" class="btn btn-outline-golden" onclick="saveDraft(7); return false;">Save Progress</button>
            </div>
        </div>
    </div>
</div>