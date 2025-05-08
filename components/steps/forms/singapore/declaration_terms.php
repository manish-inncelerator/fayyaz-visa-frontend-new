<?php
if (!isset($decryptedId) || empty($decryptedId)) {
    echo "Error: Decrypted ID is missing.";
    exit;
}

// Fetch antecedent information
$declaration_terms = $database->get('declaration_terms', '*', [
    'traveler_id' => $decryptedId,
    'order_id' => $order_id
]);

// Check if $declaration_terms is null or not
if ($declaration_terms === null) {
    $declaration_terms = [];
}
?>

<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-patch-check me-2"></i> Declaration and Terms</h5>
    </div>
    <div class="card-body">
        <div class="row g-3 needs-validation" novalidate>
            <!-- Declaration -->
            <div class="col-12">
                <div class="d-flex align-items-center gap-3 border p-3">
                    <div class="flex-grow-1">
                        <strong>*I hereby declare that all information provided is correct and accurate.</strong>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="declaration_agreed" value="yes" <?= isset($declaration_terms['declaration_agreed']) && htmlspecialchars($declaration_terms['declaration_agreed']) == 'yes' ? 'checked' : ''; ?> required>
                        <label class="form-check-label small">I AGREE</label>
                        <div class="invalid-feedback">
                            You must agree to the declaration to proceed.
                        </div>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>

                </div>
            </div>

            <!-- Terms and Conditions -->
            <div class="col-12">
                <div class="alert alert-warning">
                    <small>
                        *Please check the v14a form from the ICA website to ensure all information has been added is correct.<br>
                    </small>
                </div>
            </div>

            <!-- Additional Terms -->
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="responsibility_for_errors" value="yes" <?= isset($declaration_terms['responsibility_for_errors']) && htmlspecialchars($declaration_terms['responsibility_for_errors']) == 'yes' ? 'checked' : ''; ?> required>
                            <label class="form-check-label">
                                (1) If any information is incorrect, it is the responsibility of the agent/applicant.
                            </label>
                            <div class="invalid-feedback">
                                You must agree to this term to proceed.
                            </div>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="untrue_information_penalty" value="yes" <?= isset($declaration_terms['untrue_information_penalty']) && htmlspecialchars($declaration_terms['untrue_information_penalty']) == 'yes' ? 'checked' : ''; ?> required>
                            <label class="form-check-label">
                                (2) Any false or erroneous information will result in penalties from Fayyaz Travels and ICA respectively based on discretion.
                            </label>
                            <div class="invalid-feedback">
                                You must agree to this term to proceed.
                            </div>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="submission_verification" value="yes" <?= isset($declaration_terms['submission_verification']) && htmlspecialchars($declaration_terms['submission_verification']) == 'yes' ? 'checked' : ''; ?> required>
                            <label class="form-check-label">
                                (3) All applications received need to be checked and verified by the agent, if there is an error in submission from FayyazTravels it needs to be reported within 24 hrs of the E-Visa being received to avoid inconvenience to the client. Any errors reported after 24hrs will be the responsibility of agent/applicant.
                            </label>
                            <div class="invalid-feedback">
                                You must agree to this term to proceed.
                            </div>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="fraudulent_application_suspension" value="yes" <?= isset($declaration_terms['fraudulent_application_suspension']) && htmlspecialchars($declaration_terms['fraudulent_application_suspension']) == 'yes' ? 'checked' : ''; ?> required>
                            <label class="form-check-label">
                                (4) If there is any fraudulent or suspicious application submitted, your account will be suspended immediately upon ICA's first notice until an investigation is complete. Account will resume normal function only when the agent has been cleared.
                            </label>
                            <div class="invalid-feedback">
                                You must agree to this term to proceed.
                            </div>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="deposit_withdrawal" value="yes" <?= isset($declaration_terms['deposit_withdrawal']) && htmlspecialchars($declaration_terms['deposit_withdrawal']) == 'yes' ? 'checked' : ''; ?> required>
                            <label class="form-check-label">
                                (5) If found guilty, your deposit will be withdrawn and there will be no refund of remaining monies.
                            </label>
                            <div class="invalid-feedback">
                                You must agree to this term to proceed.
                            </div>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="information_verification" value="yes" <?= isset($declaration_terms['information_verification']) && htmlspecialchars($declaration_terms['information_verification']) == 'yes' ? 'checked' : ''; ?> required>
                            <label class="form-check-label">
                                (6) It is the agent's responsibility to verify all the information, documentation and interview the clients in person before submitting the information to Fayyaz Travels for their application.
                            </label>
                            <div class="invalid-feedback">
                                You must agree to this term to proceed.
                            </div>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="applicant_responsibility" value="yes" <?= isset($declaration_terms['applicant_responsibility']) && htmlspecialchars($declaration_terms['applicant_responsibility']) == 'yes' ? 'checked' : ''; ?> required>
                            <label class="form-check-label">
                                (7) Fayyaz Travels bears no responsibility if the information provided to them is incorrect from applicant or agent and all fees and penalties resulting from the same will be passed on to the agent.
                            </label>
                            <div class="invalid-feedback">
                                You must agree to this term to proceed.
                            </div>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="land_packages_interest" value="yes" <?= isset($declaration_terms['land_packages_interest']) && htmlspecialchars($declaration_terms['land_packages_interest']) == 'yes' ? 'checked' : ''; ?>>
                            <label class="form-check-label">
                                (8) Are you also looking for land packages?
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <input type="hidden" name="traveler_id" id="traveler_id" value="<?= htmlspecialchars($decryptedId); ?>">
                <button type="button" class="btn btn-outline-golden" onclick="saveDraft(8); return false;">Save Progress</button>
            </div>
        </div>
    </div>
</div>