<div class="card mx-auto my-2">
    <div class="card-header fw-bold text-muted d-flex justify-content-between align-items-center">
        <div>
            <i class="bi bi-plus-circle"></i> Add Traveler Information
        </div>
        <div>
            <button class="btn btn-outline-secondary rounded-pill showQRCodeBtn  d-none d-lg-block btn-sm text-decoration-none d-flex align-items-center" id="showQRCodeBtn">
                <i class="bi bi-qr-code me-1"></i> Scan to fill form on your phone
            </button>
        </div>
    </div>

    <div class="card-body">
        <form id="travelerForm" class="needs-validation" novalidate>
            <div id="travelerFieldsContainer"></div>
            <button type="submit" class="btn cta-button btn-lg rounded-pill p-3 plexFont fw-bold ms-auto d-block fs-6">Save and Next <i class="bi bi-chevron-right"></i></button>
        </form>
    </div>
</div>