<?php
function html_scripts(
    bool $includeJQuery = false,
    bool $includeBootstrap = true,
    array $customScripts = [],
    bool $includeSwal = false,
    bool $includeNotiflix = false
): string {
    // Array to hold all script tags
    $scripts = [];

    // Conditionally include jQuery
    if ($includeJQuery) {
        $scripts[] = '<script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>';
    }

    // Conditionally include Bootstrap JS (latest version)
    if ($includeBootstrap) {
        $scripts[] = '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>';
    }

    // Include custom scripts if provided
    if (!empty($customScripts)) {
        foreach ($customScripts as $script) {
            $scripts[] = "<script src=\"$script\"></script>";
        }
    }

    // Conditionally include SweetAlert 2
    if ($includeSwal) {
        $scripts[] = '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    }

    // Conditionally include Notiflix
    if ($includeNotiflix) {
        $scripts[] = '<script src="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/dist/notiflix-aio-3.2.8.min.js"></script>';
    }

    // Combine all scripts into a single string
    return implode("\n", $scripts);
}
