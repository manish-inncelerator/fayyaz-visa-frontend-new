<?php
// Determine the base URL
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://") . $_SERVER['HTTP_HOST'];

// Check if the environment is local
$isLocal = in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1']) || preg_match('/\.local$/', $_SERVER['HTTP_HOST']);

if ($isLocal) {
    $url = 'http://localhost/visa_f';
} else {
    $url = 'https://fayyaztravels.com/visa/';
}
?>

<nav class="navbar navbar-expand-lg navbar-glassy fixed-top">
    <div class="container-fluid d-flex align-items-center ps-3 pe-3">
        <!-- Back Button for All Screens -->
        <button class="btn btn-white" onclick="javascript:history.back();">
            <i class="bi bi-arrow-left fs-5"></i>
        </button>


        <!-- Logo -->
        <a class="navbar-brand ms-2" href="/visa/">
            <img src="<?php echo $url; ?>/assets/images/main-logo.png" alt="Fayyaz Travels Logo" height="36px">
        </a>
    </div>
</nav>