<?php
$changelog = json_decode(file_get_contents("changes.json"), true);
usort($changelog, function ($a, $b) {
    return version_compare($b['version'], $a['version']);
});
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changelog &mdash; Fayyaz Travels</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            transition: background 0.3s ease, color 0.3s ease;
        }

        .dark-mode #backButton {
            color: #fff;
        }

        .dark-mode {
            background-color: #1a1a1a;
            color: white;
        }

        .dark-mode .card {
            background-color: #333;
            color: white;
        }

        .card-header {
            font-size: 1.2rem;
            transition: background 0.3s ease;
        }

        .card {
            transform: translateY(10px);
            opacity: 0;
            transition: all 0.5s ease;
        }

        .changelog-entry {
            transition: opacity 0.3s ease-in-out;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-glassy fixed-top">
        <div class="container-fluid d-flex align-items-center ps-3 pe-3">
            <!-- Back Button for All Screens -->
            <button class="btn btn-white" id="backButton" onclick="javascript:history.back();">
                <i class="bi bi-arrow-left fs-5"></i>
            </button>


            <!-- Logo -->
            <a class="navbar-brand ms-2" href="/visa/">
                <img src="assets/images/main-logo" alt="Fayyaz Travels Logo" height="36px" id="mainLogo">
            </a>
        </div>
    </nav>

    <div class="container mt-5">

        <h1 class="text-center mb-4">📜 Project Changelog</h1>

        <div class="d-flex justify-content-between mb-3">
            <input type="text" id="search" class="form-control w-50" placeholder="🔍 Search by version or keyword">
            <button class="btn btn-dark" id="toggleTheme">Toggle Dark Mode</button>
        </div>

        <div class="changelog" id="changelogContainer">
            <?php foreach ($changelog as $entry): ?>
                <div class="card mb-3 changelog-entry" data-version="<?= $entry['version'] ?>" data-changes="<?= implode(' ', $entry['changes']) ?>">
                    <div class="card-header bg-primary bg-gradient text-white">
                        <strong>Version: <?= $entry['version'] ?></strong>
                        <span class="float-end fw-bold small text-white"> <?= date("jS M Y", strtotime($entry['date'])) ?></span>
                    </div>
                    <div class="card-body">
                        <ul class="card-text">
                            <?php foreach ($entry['changes'] as $change): ?>
                                <li><?= $change ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleButton = document.getElementById("toggleTheme");
            const searchInput = document.getElementById("search");
            const changelogEntries = document.querySelectorAll(".changelog-entry");
            const body = document.body;
            const logo = document.getElementById("mainLogo");


            // Update Logo
            function updateLogo() {
                logo.src = body.classList.contains("dark-mode") ?
                    "assets/images/main-logo-white.png" :
                    "assets/images/main-logo.png";
            }

            // Load Dark Mode Preference
            if (localStorage.getItem("darkMode") === "enabled") {
                body.classList.add("dark-mode");
                updateLogo();
            }



            // Toggle Dark Mode
            toggleButton.addEventListener("click", function() {
                body.classList.toggle("dark-mode");
                localStorage.setItem("darkMode", body.classList.contains("dark-mode") ? "enabled" : "disabled");
                updateLogo();
            });

            // Search Functionality
            searchInput.addEventListener("keyup", function() {
                const query = this.value.toLowerCase();
                changelogEntries.forEach(entry => {
                    const version = entry.getAttribute("data-version").toLowerCase();
                    const changes = entry.getAttribute("data-changes").toLowerCase();
                    entry.style.display = version.includes(query) || changes.includes(query) ? "block" : "none";
                });
            });

            // Lazy Loading (Performance Optimization)
            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = 1;
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.2
            });

            changelogEntries.forEach(entry => {
                entry.style.opacity = 0;
                observer.observe(entry);
            });
        });
    </script>
</body>

</html>