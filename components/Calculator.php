<form action="api/v1/post_order.php" method="POST" class="needs-validation" novalidate>
    <div class="card mb-2 border rounded-3">
        <div class="card-body p-4">
            <!-- Visa Guarantee -->
            <div class="guarantee-alert mb-4">
                <div class="d-flex align-items-center">
                    <i class="bi bi-calendar-event fs-3 iconnn me-3"></i>
                    <div>
                        <div class="fw-bold">Visa Processing Timeline</div>
                        <div class="text-secondary">
                            <?= $visaDetail['processing_time_value']; ?> <?= $visaDetail['processing_time_unit']; ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Journey Dates -->
            <div class="date-picker-section mb-4">
                <p class="alert alert-warning text-justify"><i class="bi bi-info-circle"></i> These dates can be approximate and are only required to get you a visa. You may make changes later as per visa issuance period.
                </p>
                <div class="section-title mb-3"><i class="bi bi-calendar-event"></i> Tentative Travel Dates</div>
                <div class="row g-3">
                    <!-- Date of Journey -->
                    <div class="col-md-12">
                        <div class="date-picker-card border rounded-3">
                            <div class="card-body">
                                <label class="form-label" for="dateOfJourney">
                                    <i class="bi bi-calendar-event"></i> Tentative Date of Journey
                                </label>
                                <input type="date"
                                    class="form-control"
                                    name="date_of_journey"
                                    id="dateOfJourney"
                                    required>
                                <div class="invalid-feedback">
                                    Please select your journey date
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Date of Arrival -->
                    <div class="col-md-12">
                        <div class="date-picker-card border rounded-3">
                            <div class="card-body">
                                <label class="form-label" for="dateOfArrival">
                                    <i class="bi bi-calendar-check"></i> Tentative Date of Arrival
                                </label>
                                <input type="date"
                                    class="form-control"
                                    name="date_of_arrival"
                                    id="dateOfArrival"
                                    required>
                                <div class="invalid-feedback">
                                    Please select a valid arrival date
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Rest of the form remains the same -->
            <!-- Travellers -->
            <div class="traveller-card mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-people"></i> Travellers</span>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn counter-btn" onclick="updateTravellers(-1)">-</button>
                        <span class="counter-display" id="travellerCount">1</span>
                        <input type="hidden" name="traveller_count" id="travellerInput" value="1">
                        <button type="button" class="btn counter-btn" onclick="updateTravellers(1)">+</button>
                    </div>
                </div>
            </div>
            <!-- Price Details -->
            <div class="section-title mb-3">Price</div>

            <?php
            $results = $database->select(
                "countries",
                [
                    "[>]pricing_details" => ["id" => "country_id"]
                ],
                [
                    "countries.id",
                    "countries.name",
                    "pricing_details.price",
                    "pricing_details.currency"
                ]
            );

            ?>

            <!-- Total -->
            <div class="fee-row d-flex justify-content-between align-items-center mb-4">
                <div class="section-title mb-0">Total Amount</div>
                <div class="price-tag">
                    S$<span id="totalAmount">
                        <?= number_format((float)$visaDetail['embassy_fee'] + (float)$visaDetail['portify_fees'] + (float)$visaDetail['vfs_service_fees'], 2); ?>
                    </span>
                </div>
            </div>
            <!-- Submit Button -->
            <input type="hidden" name="country_id" id="country_id" value="<?= $visaDetail['id']; ?>">
            <button type="submit" class="btn cta-button w-100 btn-lg rounded-pill p-4 plexFont fw-bold" id="applyButton">
                Start Application <i class="bi bi-arrow-right-circle fs-4 ms-1"></i>
            </button>

            <!-- <div class="divider">
                <span class="px-2 bg-white text-muted fw-bold">OR</span>
            </div>

            <a class="btn btn-lg rounded-pill w-100 mt-2 p-4 plexFont d-flex justify-content-between align-items-center text-white"
                style="background:linear-gradient(120deg, #f10087, #009ae7); border: none;">
                <div class="w-100 text-left">
                    <span class="fw-bold fs-4 plexFont">Express Service</span>
                    <p class="mt-2 mb-0 h6">Visa in 20 days</p>
                </div>
                <span class="fw-bold fs-3 alterFont text-golden-light">S$699</span>
            </a> -->

        </div>
    </div>
</form>