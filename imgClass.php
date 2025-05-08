<?php

declare(strict_types=1);

class ImageProcessor
{
    private const CACHE_DIR = 'cache/cached_images/';
    private const MAX_CACHE_AGE = 86400; // 24 hours in seconds

    public function __construct(private string $originalImagePath)
    {
        $this->ensureCacheDirectoryExists();
        $this->cleanUpOldCache();
    }

    private function ensureCacheDirectoryExists(): void
    {
        !is_dir(self::CACHE_DIR) && mkdir(self::CACHE_DIR, 0755, true);
    }

    private function cleanUpOldCache(): void
    {
        // Clean up old cache files (older than 24 hours)
        array_map(fn($file) => is_file($file) && time() - filemtime($file) >= self::MAX_CACHE_AGE && unlink($file), glob(self::CACHE_DIR . '*'));
    }

    private function getCachedImageFilename(int|string $width, int|string $height, string $format, int $quality): string
    {
        return self::CACHE_DIR . md5(basename($this->originalImagePath) . "_{$width}_{$height}_{$format}_{$quality}") . ".$format";
    }

    public function processImage(int|string $width = 'auto', int|string $height = 'auto', string $format = 'jpg', int $quality = 80): string
    {
        $cachedImage = $this->getCachedImageFilename($width, $height, $format, $quality);

        // Check if cached image already exists
        if (file_exists($cachedImage)) {
            return $cachedImage;
        }

        // Check if the original image exists
        if (!file_exists($this->originalImagePath)) {
            throw new Exception("Original image not found: {$this->originalImagePath}");
        }

        // Get the image dimensions and type
        [$originalWidth, $originalHeight, $type] = getimagesize($this->originalImagePath);

        // Load the image based on its type
        $sourceImage = match ($type) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($this->originalImagePath),
            IMAGETYPE_PNG  => imagecreatefrompng($this->originalImagePath),
            IMAGETYPE_GIF  => imagecreatefromgif($this->originalImagePath),
            IMAGETYPE_WEBP => imagecreatefromwebp($this->originalImagePath),
            IMAGETYPE_AVIF => function_exists('imagecreatefromavif') ? imagecreatefromavif($this->originalImagePath) : throw new Exception("AVIF not supported"),
            default        => throw new Exception("Unsupported image type"),
        };

        // If both width and height are 'auto', return the original image without resizing
        if ($width === 'auto' && $height === 'auto') {
            return $this->originalImagePath;
        }

        // If width is 'auto', calculate it based on the height and aspect ratio
        if ($width === 'auto' && $height !== 'auto') {
            $width = $height * ($originalWidth / $originalHeight);
        }

        // If height is 'auto', calculate it based on the width and aspect ratio
        if ($height === 'auto' && $width !== 'auto') {
            $height = $width * ($originalHeight / $originalWidth);
        }

        // Create the destination image with the new dimensions
        $destinationImage = imagecreatetruecolor((int)$width, (int)$height);

        // Handle transparency for PNG and GIF images
        if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_GIF) {
            imagealphablending($destinationImage, false);
            imagesavealpha($destinationImage, true);
            imagefilledrectangle($destinationImage, 0, 0, (int)$width, (int)$height, imagecolorallocatealpha($destinationImage, 255, 255, 255, 127));
        }

        // Resample the image to the new size
        imagecopyresampled($destinationImage, $sourceImage, 0, 0, 0, 0, (int)$width, (int)$height, $originalWidth, $originalHeight);

        // Output the image in the desired format
        match ($format) {
            'jpg', 'jpeg' => imagejpeg($destinationImage, $cachedImage, $quality),
            'png'         => imagepng($destinationImage, $cachedImage, (int)(9 * $quality / 100)),
            'gif'         => imagegif($destinationImage, $cachedImage),
            'webp'        => imagewebp($destinationImage, $cachedImage, $quality),
            'avif'        => function_exists('imageavif') ? imageavif($destinationImage, $cachedImage, $quality) : throw new Exception("AVIF not supported"),
            default       => throw new Exception("Unsupported output format"),
        };

        // Clean up memory
        imagedestroy($sourceImage);
        imagedestroy($destinationImage);

        // Return the cached image path
        return $cachedImage;
    }
}
