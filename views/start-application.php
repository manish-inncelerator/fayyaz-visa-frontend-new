<?php

defined('BASE_DIR') || die('Direct access denied');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Redirect to home if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: ../home');
    exit;
}

// Include required files
require 'inc/html_head.php';
require 'inc/html_foot.php';
require 'database.php';
require 'min.php';

// country details
$cid = isset($_GET['cid']) ? $_GET['cid'] : null;
$pid = isset($_GET['pid']) ? $_GET['pid'] : null;

$visaDetail = $database->get("visa_countries", [
    "[<]visa_pricing" => ["country_id" => "country_id"]
], "*", [
    "visa_countries.country_id" => $cid,
    "visa_pricing.id" => $pid
]);

// Output HTML head and scripts
echo html_head('Start Application', null, true, ['assets/css/visa.css', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css', 'https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/themes/airbnb.min.css'], true);
?>


<style>
    .flatpickr-day.disabled {
        background-color: #f8d7da !important;
        color: #721c24 !important;
    }

    /* Weekends - Orange */
    .flatpickr-day.weekend {
        background-color: #fff3cd !important;
        color: #856404 !important;
    }

    .flatpickr-day.available:not(.disabled):not(.weekend) {
        background-color: #d4edda !important;
        color: #155724 !important;
    }
</style>
<!-- Navbar -->
<?php require 'components/SimpleNavbar.php'; ?>
<!-- ./Navbar -->

<!-- Login Section -->
<section class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="row w-100 justify-content-center">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="form-container rounded-3 bg-white">
                <form id="visaForm" action="api/v1/post_order.php" method="POST" class="needs-validation" novalidate>
                    <div class="card mb-2 border rounded-3">
                        <div class="card-body p-4">
                            <!-- Visa Guarantee -->
                            <div class="guarantee-alert mb-4">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar-event fs-3 iconnn me-3"></i>
                                    <div>
                                        <div class="fw-bold">Visa Processing Timeline</div>
                                        <div class="text-secondary">
                                            <?= $visaDetail['visa_processing_time']; ?> Days
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
                                                <input type="text"
                                                    class="form-control"
                                                    name="date_of_journey"
                                                    id="dateOfJourney"
                                                    placeholder="Select journey date" aria-label="Date of Journey"
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
                                                    <i class="bi bi-calendar-check"></i> Tentative Date of Return
                                                </label>
                                                <input type="text"
                                                    class="form-control"
                                                    name="date_of_arrival"
                                                    id="dateOfArrival"
                                                    placeholder="Select arrival date" aria-label="Date of Arrival"
                                                    required disabled>
                                                <div class="invalid-feedback">
                                                    Please select a valid arrival date
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                            <div class="section-title mb-3">Price Breakdown</div>

                            <ul class="list-group s">
                                <?php if ($visaDetail['embassy_fee'] > 0): ?>
                                    <li class="list-group-item small">Embassy Fee <span class="fw-bold float-end">S$ <?= $visaDetail['embassy_fee']; ?> x <span class="traveller_count">1</span></span></li>
                                <?php endif; ?>
                                <?php if ($visaDetail['visa_assistance_fee'] > 0): ?>
                                    <li class="list-group-item small">Visa Assistance Fee <span class="fw-bold float-end">S$ <?= $visaDetail['visa_assistance_fee']; ?> x <span class="traveller_count">1</span></span></li>
                                <?php endif; ?>
                                <?php if ($visaDetail['vfs_service_fee'] > 0): ?>
                                    <li class="list-group-item small">Admin Fee <span class="fw-bold float-end">S$ <?= $visaDetail['vfs_service_fee']; ?> x <span class="traveller_count">1</span></span></li>
                                <?php endif; ?>
                                <?php if ($visaDetail['single_entry_fee'] > 0): ?>
                                    <li class="list-group-item small">Single Entry Fee <span class="fw-bold float-end">S$ <?= $visaDetail['single_entry_fee']; ?> x <span class="traveller_count">1</span></span></li>
                                <?php endif; ?>
                                <?php if ($visaDetail['multiple_entry_fee'] > 0): ?>
                                    <li class="list-group-item small">Multiple Entry Fee <span class="fw-bold float-end">S$ <?= $visaDetail['multiple_entry_fee']; ?> x <span class="traveller_count">1</span></span></li>
                                <?php endif; ?>
                                <?php if ($visaDetail['visa_on_arrival_fee'] > 0): ?>
                                    <li class="list-group-item small">Visa on Arrival Fee <span class="fw-bold float-end">S$ <?= $visaDetail['visa_on_arrival_fee']; ?> x <span class="traveller_count">1</span></span></li>
                                <?php endif; ?>
                            </ul>


                            <!-- Total Amount -->
                            <div class="fee-row d-flex justify-content-between align-items-center mb-4">
                                <div class="section-title mb-0 fw-bold">Total Amount</div>
                                <div class="price-tag fw-bold fs-5">S$ <?= $visaDetail['total_pricing']; ?></div>
                            </div>

                            <!-- Submit Button -->
                            <input type="hidden" name="country_id" id="country_id" value="<?= $visaDetail['country_id']; ?>">

                            <!-- Visible return date (formatted) -->
                            <input type="hidden" id="dateOfReturn" class="form-control" readonly>

                            <!-- Hidden return date (Y-m-d for backend) -->
                            <input type="hidden" id="returnDateHidden" name="return_date">



                            <input type="hidden" name="pricing_id" id="pricing_id" value="<?= trim(strip_tags($_GET['pid'] ?? '0')); ?>">


                            <input type="hidden" name="hu" id="hu" value="<?= $hu; ?>">
                            <button type="submit" class="btn cta-button w-100 btn-lg rounded-pill p-4 plexFont fw-bold" id="submitBtn">
                                Start Application <i class="bi bi-arrow-right-circle fs-4 ms-1"></i>
                            </button>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php
// Output HTML scripts
echo html_scripts(
    includeJQuery: false,
    includeBootstrap: true,
    customScripts: ['https://cdn.jsdelivr.net/npm/flatpickr'],
    includeSwal: false,
    includeNotiflix: true
);
?>



<script>
    function updateTravellers(change) {
        let countElement = document.getElementById("travellerCount");
        let inputElement = document.getElementById("travellerInput");
        let travellerElements = document.querySelectorAll(".traveller_count");
        let totalAmountElement = document.querySelector(".price-tag");
        let submitButton = document.getElementById("submitBtn");
        let form = document.getElementById("visaForm");
        let count = parseInt(countElement.textContent) + change;

        if (count < 1) count = 1;
        if (count > 6) {
            submitButton.disabled = true;
        } else {
            submitButton.disabled = false;
        }

        countElement.textContent = count;
        inputElement.value = count;
        travellerElements.forEach(el => el.textContent = `${count}`);

        // Price calculations
        let embassyFee = parseFloat("<?= $visaDetail['embassy_fee'] ?? 0; ?>");
        let visaAssistanceFee = parseFloat("<?= $visaDetail['visa_assistance_fee'] ?? 0; ?>");
        let vfsServiceFee = parseFloat("<?= $visaDetail['vfs_service_fee'] ?? 0; ?>");
        let singleEntryFee = parseFloat("<?= $visaDetail['single_entry_fee'] ?? 0; ?>");
        let multipleEntryFee = parseFloat("<?= $visaDetail['multiple_entry_fee'] ?? 0; ?>");
        let visaOnArrivalFee = parseFloat("<?= $visaDetail['visa_on_arrival_fee'] ?? 0; ?>");

        let totalPrice = (embassyFee + visaAssistanceFee + vfsServiceFee + singleEntryFee + multipleEntryFee + visaOnArrivalFee) * count;

        totalAmountElement.textContent = `S$ ${isNaN(totalPrice) ? '0.00' : totalPrice.toFixed(2)}`;
    }

    // Bootstrap Form Validation
    document.getElementById("visaForm").addEventListener("submit", function(event) {
        if (!this.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        this.classList.add("was-validated");
    });
</script>




<!-- Calendar -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.needs-validation');
        const processingTime = parseInt("<?= $visaDetail['visa_processing_time']; ?>");

        const today = new Date();
        today.setHours(0, 0, 0, 0);

        const formatDate = (date) => date.toISOString().split('T')[0];

        // Generate today + processingTime business days
        function generateProcessingTimeBlockDates(startDate, businessDays) {
            const dates = [];
            const date = new Date(startDate);
            let remaining = businessDays;

            while (remaining >= 0) {
                if (date.getDay() !== 0 && date.getDay() !== 6) {
                    dates.push(formatDate(new Date(date)));
                    remaining--;
                }
                date.setDate(date.getDate() + 1);
            }
            return dates;
        }

        const disabledProcessingDates = generateProcessingTimeBlockDates(today, processingTime);
        const allDisabledDatesSet = new Set(disabledProcessingDates);

        const adjustToNextWeekday = (date) => {
            while (date.getDay() === 0 || date.getDay() === 6 || allDisabledDatesSet.has(formatDate(date))) {
                date.setDate(date.getDate() + 1);
            }
            return date;
        };

        const addBusinessDays = (startDate, days) => {
            const result = new Date(startDate);
            let remainingDays = days;
            while (remainingDays > 0) {
                result.setDate(result.getDate() + 1);
                if (result.getDay() !== 0 && result.getDay() !== 6) {
                    remainingDays--;
                }
            }
            return adjustToNextWeekday(result);
        };

        const minJourneyDate = adjustToNextWeekday(new Date(disabledProcessingDates[disabledProcessingDates.length - 1]));
        const maxDate = new Date(today);
        maxDate.setMonth(maxDate.getMonth() + 3);

        // Initialize picker variables
        let journeyPicker, arrivalPicker;

        // Create styling function that doesn't depend on picker instances
        const getDayStyleInfo = (date, isArrivalPicker = false) => {
            const fDate = formatDate(date);
            const isWeekend = date.getDay() === 0 || date.getDay() === 6;
            const isDisabledProcessing = allDisabledDatesSet.has(fDate);
            const isPast = date < today;

            const styleInfo = {
                backgroundColor: '#d4edda', // default available
                color: '#155724',
                title: 'Available'
            };

            if (isArrivalPicker && !journeyPicker?.selectedDates[0]) {
                return {
                    backgroundColor: '#f8f9fa',
                    color: '#6c757d',
                    title: 'Select journey date first'
                };
            }

            if (isArrivalPicker && journeyPicker?.selectedDates[0]) {
                const minArrivalDate = addBusinessDays(journeyPicker.selectedDates[0], 5);
                if (fDate === formatDate(minArrivalDate)) {
                    return {
                        backgroundColor: '#ffc107',
                        color: '#856404',
                        title: 'Minimum processing time - select a later date'
                    };
                }
                if (fDate < formatDate(minArrivalDate)) {
                    if (isWeekend) {
                        return {
                            backgroundColor: '#fff3cd',
                            color: '#856404',
                            title: 'Past date'
                        };
                    }
                    return {
                        backgroundColor: '#f8d7da',
                        color: '#721c24',
                        title: 'Past date'
                    };
                }
            }

            if (isDisabledProcessing || isPast) {
                styleInfo.backgroundColor = '#f8d7da';
                styleInfo.color = '#721c24';
                styleInfo.title = isDisabledProcessing ? 'Visa processing – pick later' : 'Past date';
            } else if (isWeekend) {
                styleInfo.backgroundColor = '#fff3cd';
                styleInfo.color = '#856404';
                styleInfo.title = 'Weekend – unavailable';
            }

            return styleInfo;
        };

        // Initialize journey picker first
        journeyPicker = flatpickr("#dateOfJourney", {
            dateFormat: "Y-m-d",
            minDate: minJourneyDate,
            maxDate: maxDate,
            disableMobile: true,
            disable: [
                function(date) {
                    return date.getDay() === 0 ||
                        date.getDay() === 6 ||
                        allDisabledDatesSet.has(formatDate(date)) ||
                        date < today;
                }
            ],
            onChange: function(selectedDates) {
                if (selectedDates[0]) {
                    const journeyDate = selectedDates[0];
                    const minArrivalDate = addBusinessDays(journeyDate, 5);
                    const dateToDisableStr = formatDate(minArrivalDate);

                    arrivalPicker.set('disable', [
                        function(date) {
                            const fDate = formatDate(date);
                            const isWeekend = date.getDay() === 0 || date.getDay() === 6;
                            const isPast = date < today;
                            const isDisabledProcessing = allDisabledDatesSet.has(fDate);
                            const isBeforeJourney = date <= journeyDate;

                            return isWeekend || isPast || isDisabledProcessing || isBeforeJourney ||
                                fDate === dateToDisableStr;
                        }
                    ]);

                    arrivalPicker.set('minDate', addBusinessDays(minArrivalDate, 1));
                    arrivalPicker.setDate(addBusinessDays(minArrivalDate, 1));
                    document.getElementById('dateOfArrival').removeAttribute('disabled');
                    arrivalPicker.redraw();
                } else {
                    arrivalPicker.set('disable', [function() {
                        return true;
                    }]);
                    arrivalPicker.clear();
                    document.getElementById('dateOfArrival').setAttribute('disabled', 'disabled');
                    arrivalPicker.redraw();
                }
            },
            onDayCreate: function(dObj, dStr, fp, dayElem) {
                const style = getDayStyleInfo(dayElem.dateObj);
                Object.assign(dayElem.style, {
                    backgroundColor: style.backgroundColor,
                    color: style.color
                });
                dayElem.setAttribute('title', style.title);
            }
        });

        // Initialize arrival picker after journey picker
        arrivalPicker = flatpickr("#dateOfArrival", {
            dateFormat: "Y-m-d",
            disableMobile: true,
            disable: [function() {
                return true; // Initially disable arrival picker
            }],
            onDayCreate: function(dObj, dStr, fp, dayElem) {
                const style = getDayStyleInfo(dayElem.dateObj, true);
                Object.assign(dayElem.style, {
                    backgroundColor: style.backgroundColor,
                    color: style.color
                });
                dayElem.setAttribute('title', style.title);
            }
        });

        // Form validation
        form.addEventListener('submit', function(event) {
            const journeyInput = document.getElementById('dateOfJourney');
            const arrivalInput = document.getElementById('dateOfArrival');
            let formValid = true;

            if (!journeyPicker.selectedDates[0]) {
                journeyInput.classList.add('is-invalid');
                formValid = false;
            } else {
                journeyInput.classList.remove('is-invalid');
            }

            if (!arrivalPicker.selectedDates[0]) {
                arrivalInput.classList.add('is-invalid');
                formValid = false;
            } else if (journeyPicker.selectedDates[0]) {
                const minArrivalDate = addBusinessDays(journeyPicker.selectedDates[0], 5);
                if (arrivalPicker.selectedDates[0] <= minArrivalDate) {
                    arrivalInput.classList.add('is-invalid');
                    formValid = false;
                }
            }

            if (!formValid) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add('was-validated');
        }, false);
    });
</script>
<!-- ./Calendar -->

</body>

</html>