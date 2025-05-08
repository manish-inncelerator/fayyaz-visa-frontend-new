<?php

function generateOGImage($title, $subtitle = "", $width = 1200, $height = 630, $gradient_colors = null)
{
    $image = imagecreatetruecolor($width, $height);
    $white = imagecolorallocate($image, 255, 255, 255);
    $black = imagecolorallocate($image, 0, 0, 0);

    // Define named gradients
    $named_gradients = [
        'ocean_sunset' => [
            'colors' => [[170, 220, 255], [200, 180, 255], [240, 200, 220], [255, 230, 190], [220, 255, 200]],
            'border' => [150, 200, 255]
        ],
        'tropical_dusk' => [
            'colors' => [[190, 255, 210], [170, 240, 255], [200, 200, 255], [240, 220, 255], [255, 200, 180]],
            'border' => [160, 230, 255]
        ],
        'candy_sky' => [
            'colors' => [[255, 200, 230], [220, 180, 255], [180, 220, 255], [200, 255, 220], [255, 240, 200]],
            'border' => [200, 160, 255]
        ],
        'pastel_dream' => [
            'colors' => [[255, 220, 200], [240, 200, 255], [200, 220, 255], [180, 255, 230], [220, 255, 180]],
            'border' => [180, 240, 220]
        ],
        'aurora_glow' => [
            'colors' => [[180, 255, 240], [200, 220, 255], [220, 180, 255], [255, 200, 220], [240, 255, 200]],
            'border' => [160, 255, 230]
        ],
        'desert_mirage' => [
            'colors' => [[255, 230, 180], [255, 210, 200], [240, 190, 255], [220, 200, 255], [200, 255, 220]],
            'border' => [255, 200, 180]
        ],
        'twilight_bliss' => [
            'colors' => [[200, 180, 255], [220, 200, 255], [255, 220, 200], [255, 240, 180], [180, 255, 220]],
            'border' => [180, 160, 255]
        ],
        'forest_dawn' => [
            'colors' => [[180, 255, 200], [200, 240, 180], [220, 255, 200], [255, 230, 180], [240, 200, 255]],
            'border' => [160, 230, 180]
        ],
        'berry_frost' => [
            'colors' => [[255, 200, 220], [240, 180, 255], [200, 200, 255], [180, 240, 255], [220, 255, 240]],
            'border' => [230, 160, 255]
        ],
        'citrus_breeze' => [
            'colors' => [[255, 240, 180], [255, 220, 200], [240, 255, 180], [220, 255, 200], [200, 240, 255]],
            'border' => [255, 230, 160]
        ],
        'mystic_haze' => [
            'colors' => [[200, 220, 255], [220, 200, 255], [240, 180, 255], [255, 200, 220], [180, 255, 240]],
            'border' => [180, 200, 255]
        ],
        'coral_reef' => [
            'colors' => [[255, 200, 180], [255, 220, 200], [240, 255, 220], [200, 255, 240], [180, 240, 255]],
            'border' => [255, 180, 160]
        ],
        'velvet_night' => [
            'colors' => [[180, 200, 255], [200, 180, 255], [220, 200, 255], [240, 220, 255], [255, 240, 200]],
            'border' => [160, 180, 255]
        ],
        'spring_blossom' => [
            'colors' => [[255, 220, 240], [240, 200, 255], [220, 255, 200], [200, 240, 180], [255, 230, 200]],
            'border' => [255, 200, 230]
        ],
        'glacial_mint' => [
            'colors' => [[180, 255, 240], [200, 255, 220], [220, 240, 255], [240, 255, 200], [255, 220, 180]],
            'border' => [160, 255, 220]
        ],
        'sunset_meadow' => [
            'colors' => [[255, 230, 180], [255, 210, 200], [240, 200, 255], [220, 255, 200], [200, 240, 180]],
            'border' => [255, 220, 160]
        ]
    ];

    // Gradient selection
    $selected_gradient = null;
    if ($gradient_colors === null) {
        $selected_gradient = $named_gradients[array_rand($named_gradients)];
        $gradient_colors = $selected_gradient['colors'];
    } else {
        $selected_gradient = ['colors' => $gradient_colors, 'border' => [200, 200, 255]];
    }

    // Apply gradient
    $segment_height = round($height / (count($gradient_colors) - 1));
    for ($y = 0; $y < $height; $y++) {
        $segment = min(floor($y / $segment_height), count($gradient_colors) - 2);
        $progress = ($y % $segment_height) / $segment_height;
        $r = round($gradient_colors[$segment][0] + ($gradient_colors[$segment + 1][0] - $gradient_colors[$segment][0]) * $progress);
        $g = round($gradient_colors[$segment][1] + ($gradient_colors[$segment + 1][1] - $gradient_colors[$segment][1]) * $progress);
        $b = round($gradient_colors[$segment][2] + ($gradient_colors[$segment + 1][2] - $gradient_colors[$segment][2]) * $progress);
        $color = imagecolorallocate($image, $r, $g, $b);
        imageline($image, 0, $y, $width, $y, $color);
    }

    // Add subtle pattern
    $dot_color = imagecolorallocatealpha($image, 255, 255, 255, 90);
    for ($i = 0; $i < 150; $i++) {
        imagesetpixel($image, rand(0, $width), rand(0, $height), $dot_color);
    }

    // Add borders
    $border_color = imagecolorallocate($image, $selected_gradient['border'][0], $selected_gradient['border'][1], $selected_gradient['border'][2]);
    $border_thickness = 20;
    for ($i = 0; $i < $border_thickness; $i++) {
        imagerectangle($image, $i, $i, $width - 1 - $i, $height - 1 - $i, $border_color);
    }

    // Font setup
    $font_path = __DIR__ . '/codenext.ttf';
    $font_path_regular = __DIR__ . '/cabin.ttf';

    // Define padding and margins
    $padding = $border_thickness + 50;
    $max_text_width = $width - (2 * $padding);

    // Dynamic font sizing
    $base_font_size = $width / 24; // Adjusted for better scaling
    $font_size_title = min($base_font_size, 60); // Reduced max size to ensure fit
    $font_size_subtitle = min($base_font_size / 2, 30);
    $font_size_domain = min($base_font_size / 3, 20);

    // Font color
    $middle_color = $gradient_colors[floor(count($gradient_colors) / 2)];
    $font_color = (0.299 * $middle_color[0] + 0.587 * $middle_color[1] + 0.114 * $middle_color[2]) > 186
        ? $black : $white;

    // Dynamic word wrapping with stricter limits
    $title_char_limit = floor($max_text_width / ($font_size_title * 0.5)); // Tighter wrapping
    $subtitle_char_limit = floor($max_text_width / ($font_size_subtitle * 0.5));

    // Custom wrapping function to ensure better line breaks
    function customWordWrap($text, $char_limit, $font_size, $font_path, $max_width)
    {
        $words = explode(' ', $text);
        $lines = [];
        $current_line = '';

        foreach ($words as $word) {
            $test_line = $current_line ? $current_line . ' ' . $word : $word;
            $bbox = imagettfbbox($font_size, 0, $font_path, $test_line);
            $text_width = $bbox[2] - $bbox[0];

            if ($text_width <= $max_width) {
                $current_line = $test_line;
            } else {
                if ($current_line) {
                    $lines[] = $current_line;
                }
                $current_line = $word;
            }
        }

        if ($current_line) {
            $lines[] = $current_line;
        }

        return $lines;
    }

    $title_lines = customWordWrap($title, $title_char_limit, $font_size_title, $font_path, $max_text_width);
    $subtitle_lines = customWordWrap($subtitle, $subtitle_char_limit, $font_size_subtitle, $font_path_regular, $max_text_width);

    if (file_exists($font_path) && file_exists($font_path_regular)) {
        // Calculate text block height
        $line_spacing = 1.2;
        $title_height = count($title_lines) * ($font_size_title * $line_spacing);
        $subtitle_height = count($subtitle_lines) * ($font_size_subtitle * $line_spacing);
        $total_text_height = $title_height + ($subtitle ? $subtitle_height + 30 : 0);

        $y_start = max($padding, ($height - $total_text_height) / 2);

        // Add title
        $current_y = $y_start;
        foreach ($title_lines as $line) {
            $bbox = imagettfbbox($font_size_title, 0, $font_path, $line);
            $text_width = $bbox[2] - $bbox[0];
            $x_position = round(($width - $text_width) / 2);
            imagettftext($image, $font_size_title, 0, $x_position, $current_y, $font_color, $font_path, $line);
            $current_y += $font_size_title * $line_spacing;
        }

        // Add subtitle
        if ($subtitle) {
            $current_y += 30;
            foreach ($subtitle_lines as $line) {
                $bbox = imagettfbbox($font_size_subtitle, 0, $font_path_regular, $line);
                $text_width = $bbox[2] - $bbox[0];
                $x_position = round(($width - $text_width) / 2);
                imagettftext($image, $font_size_subtitle, 0, $x_position, $current_y, $font_color, $font_path_regular, $line);
                $current_y += $font_size_subtitle * $line_spacing;
            }
        }

        // Add domain
        $domain = "fayyaztravels.com/visa";
        $domain_y = $height - $padding + 20;
        imagettftext($image, $font_size_domain, 0, $padding, $domain_y, $font_color, $font_path_regular, $domain);

        // Add logo
        $logo_path = 'main-logo.png';
        if (file_exists($logo_path)) {
            $logo = imagecreatefrompng($logo_path);
            $logo_width = imagesx($logo);
            $logo_height = imagesy($logo);
            $logo_scale = min(100 / $logo_width, 100 / $logo_height);
            $new_logo_width = round($logo_width * $logo_scale);
            $new_logo_height = round($logo_height * $logo_scale);
            imagecopyresampled(
                $image,
                $logo,
                $width - $new_logo_width - $padding,
                $height - $new_logo_height - $padding + 20,
                0,
                0,
                $new_logo_width,
                $new_logo_height,
                $logo_width,
                $logo_height
            );
            imagedestroy($logo);
        }
    } else {
        // Fallback GD font
        $font = 5;
        $font_small = 3;
        $title_height = count($title_lines) * 40;
        $subtitle_height = count($subtitle_lines) * 20;
        $total_text_height = $title_height + ($subtitle ? $subtitle_height + 20 : 0);
        $y_start = max($padding, ($height - $total_text_height) / 2);

        $current_y = $y_start;
        foreach ($title_lines as $line) {
            $text_width = imagefontwidth($font) * strlen($line);
            $x_position = round(($width - $text_width) / 2);
            imagestring($image, $font, $x_position, $current_y, $line, $font_color);
            $current_y += 40;
        }

        if ($subtitle) {
            $current_y += 20;
            foreach ($subtitle_lines as $line) {
                $text_width = imagefontwidth($font_small) * strlen($line);
                $x_position = round(($width - $text_width) / 2);
                imagestring($image, $font_small, $x_position, $current_y, $line, $font_color);
                $current_y += 20;
            }
        }

        $domain = "fayyaztravels.com/visa";
        $domain_y = $height - $padding;
        imagestring($image, 3, $padding, $domain_y, $domain, $font_color);
    }

    header('Content-Type: image/png');
    imagepng($image);
    imagedestroy($image);
}

// Handle GET requests
$fixed_get = [];
foreach ($_GET as $key => $value) {
    $clean_key = str_replace('amp;', '', $key);
    $fixed_get[$clean_key] = $value;
}

if (isset($_GET['title'])) {
    $title = isset($fixed_get['title']) ? html_entity_decode($fixed_get['title'], ENT_QUOTES, 'UTF-8') : '';
    $subtitle = isset($fixed_get['subtitle']) ? html_entity_decode($fixed_get['subtitle'], ENT_QUOTES, 'UTF-8') : '';

    $gradient_colors = null;
    if (isset($fixed_get['gradient'])) {
        $gradient_input = html_entity_decode($fixed_get['gradient'], ENT_QUOTES, 'UTF-8');
        $named_gradients = [
            'ocean_sunset' => ['colors' => [[170, 220, 255], [200, 180, 255], [240, 200, 220], [255, 230, 190], [220, 255, 200]], 'border' => [150, 200, 255]],
            'tropical_dusk' => ['colors' => [[190, 255, 210], [170, 240, 255], [200, 200, 255], [240, 220, 255], [255, 200, 180]], 'border' => [160, 230, 255]],
            'candy_sky' => ['colors' => [[255, 200, 230], [220, 180, 255], [180, 220, 255], [200, 255, 220], [255, 240, 200]], 'border' => [200, 160, 255]],
            'pastel_dream' => ['colors' => [[255, 220, 200], [240, 200, 255], [200, 220, 255], [180, 255, 230], [220, 255, 180]], 'border' => [180, 240, 220]],
            'aurora_glow' => ['colors' => [[180, 255, 240], [200, 220, 255], [220, 180, 255], [255, 200, 220], [240, 255, 200]], 'border' => [160, 255, 230]],
            'desert_mirage' => ['colors' => [[255, 230, 180], [255, 210, 200], [240, 190, 255], [220, 200, 255], [200, 255, 220]], 'border' => [255, 200, 180]],
            'twilight_bliss' => ['colors' => [[200, 180, 255], [220, 200, 255], [255, 220, 200], [255, 240, 180], [180, 255, 220]], 'border' => [180, 160, 255]],
            'forest_dawn' => ['colors' => [[180, 255, 200], [200, 240, 180], [220, 255, 200], [255, 230, 180], [240, 200, 255]], 'border' => [160, 230, 180]],
            'berry_frost' => ['colors' => [[255, 200, 220], [240, 180, 255], [200, 200, 255], [180, 240, 255], [220, 255, 240]], 'border' => [230, 160, 255]],
            'citrus_breeze' => ['colors' => [[255, 240, 180], [255, 220, 200], [240, 255, 180], [220, 255, 200], [200, 240, 255]], 'border' => [255, 230, 160]],
            'mystic_haze' => ['colors' => [[200, 220, 255], [220, 200, 255], [240, 180, 255], [255, 200, 220], [180, 255, 240]], 'border' => [180, 200, 255]],
            'coral_reef' => ['colors' => [[255, 200, 180], [255, 220, 200], [240, 255, 220], [200, 255, 240], [180, 240, 255]], 'border' => [255, 180, 160]],
            'velvet_night' => ['colors' => [[180, 200, 255], [200, 180, 255], [220, 200, 255], [240, 220, 255], [255, 240, 200]], 'border' => [160, 180, 255]],
            'spring_blossom' => ['colors' => [[255, 220, 240], [240, 200, 255], [220, 255, 200], [200, 240, 180], [255, 230, 200]], 'border' => [255, 200, 230]],
            'glacial_mint' => ['colors' => [[180, 255, 240], [200, 255, 220], [220, 240, 255], [240, 255, 200], [255, 220, 180]], 'border' => [160, 255, 220]],
            'sunset_meadow' => ['colors' => [[255, 230, 180], [255, 210, 200], [240, 200, 255], [220, 255, 200], [200, 240, 180]], 'border' => [255, 220, 160]]
        ];

        if (array_key_exists($gradient_input, $named_gradients)) {
            $gradient_colors = $named_gradients[$gradient_input]['colors'];
            $selected_gradient = $named_gradients[$gradient_input];
        } else {
            $colors = explode('-', $gradient_input);
            $gradient_colors = [];
            foreach ($colors as $color) {
                $rgb = explode(',', $color);
                if (count($rgb) === 3) {
                    $gradient_colors[] = [intval($rgb[0]), intval($rgb[1]), intval($rgb[2])];
                }
            }
            if (count($gradient_colors) < 2) {
                $gradient_colors = null;
            } else {
                $selected_gradient = ['colors' => $gradient_colors, 'border' => [200, 200, 255]];
            }
        }
    }

    generateOGImage($title, $subtitle, 1200, 630, $gradient_colors);
}
