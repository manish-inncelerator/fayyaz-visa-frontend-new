<?php

class PageCache
{
    private $cacheFolder = 'cache/minify/';
    private $defaultCacheTime = 18000; // 5 hours in seconds
    private $monitoredFolders = [
        __DIR__ . '/components',
        __DIR__ . '/inc',
        __DIR__ . '/assets',
        __DIR__ . '/views'
    ];
    private $currentPage;
    private $cacheTime;
    private $cacheVersion = 'v1'; // Cache version for easy invalidation
    private $language;
    private $userSession;
    private $logFile = 'cache_log.txt'; // Log file for errors

    public function __construct($currentPage = null, $cacheTime = null, $language = null, $userSession = null)
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $this->currentPage = $currentPage ?? $_SERVER['SCRIPT_FILENAME'];
        $this->cacheTime = $cacheTime ?? $this->defaultCacheTime;
        $this->language = $language ?? 'en'; // Default language is English
        $this->userSession = $userSession ?? session_id(); // Cache per session by default

        $this->ensureCacheFolderExists();
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); // Ensure the session is started
        }
    }

    // Ensure cache folder exists and is writable
    private function ensureCacheFolderExists()
    {
        if (!is_dir($this->cacheFolder)) {
            if (!mkdir($this->cacheFolder, 0777, true) && !is_writable($this->cacheFolder)) {
                $this->logError("Cache folder is not writable or could not be created.");
                die("Cache folder is not writable or could not be created.");
            }
        }
        if (!is_writable($this->cacheFolder)) {
            $this->logError("Cache folder is not writable.");
            die("Cache folder is not writable.");
        }
    }

    // Get a clean slug from the current URL with language and session ID
    private function getSlug()
    {
        $slug = preg_replace('/[^a-zA-Z0-9_-]/', '-', trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), '/'));
        return "{$slug}_{$this->language}_{$this->userSession}_{$this->cacheVersion}";
    }

    // Get the cache file path
    private function getCacheFile()
    {
        return $this->cacheFolder . $this->getSlug() . '.html';
    }

    // Get the last modified time of a folder
    private function getLastModifiedTime($folder)
    {
        $lastModifiedTime = 0;
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder, RecursiveDirectoryIterator::SKIP_DOTS)) as $file) {
            if ($file->isFile()) {
                $lastModifiedTime = max($lastModifiedTime, $file->getMTime());
            }
        }
        return $lastModifiedTime;
    }

    // Check if cache is valid
    public function isCacheValid($cacheFile)
    {
        if (!file_exists($cacheFile)) return false;

        $cacheModifiedTime = filemtime($cacheFile);
        if (time() - $this->cacheTime > $cacheModifiedTime) return false;
        if (file_exists($this->currentPage) && filemtime($this->currentPage) > $cacheModifiedTime) return false;

        foreach ($this->monitoredFolders as $folder) {
            if ($this->getLastModifiedTime($folder) > $cacheModifiedTime) return false;
        }

        return true;
    }

    // Serve cached page if valid
    public function serveCachedPage()
    {
        $cacheFile = $this->getCacheFile();
        if ($this->isCacheValid($cacheFile)) {
            // Add a comment to the HTML indicating that the cached version is being served
            $cachedContent = file_get_contents($cacheFile);
            $cachedContent = "<!-- Serving cached page from {$cacheFile} -->\n" . $cachedContent;

            header('Content-Type: text/html; charset=UTF-8');
            echo $cachedContent;
            return true; // Cache served
        }
        return false; // No valid cache found
    }

    // Start output buffering to capture content before saving as static HTML
    public function startBuffering()
    {
        if (headers_sent()) die("Headers already sent.");
        ob_start();
    }

    // Save the content as a static HTML file (no compression)
    public function saveCache($content)
    {
        $cacheFile = $this->getCacheFile();
        if (file_put_contents($cacheFile, $content) === false) {
            $this->logError("Failed to write cache file: {$cacheFile}");
            die("Failed to write cache file.");
        }
    }

    // Purge old cache files based on modified time or cache version
    public function purgeOldCache()
    {
        foreach (new DirectoryIterator($this->cacheFolder) as $fileInfo) {
            if ($fileInfo->isFile()) {
                // Purge expired cache files
                if (time() - $this->cacheTime > $fileInfo->getMTime() || strpos($fileInfo->getFilename(), $this->cacheVersion) === false) {
                    unlink($fileInfo->getRealPath());
                }
            }
        }
    }

    // Log errors to cache_log.txt
    private function logError($message)
    {
        $date = date('Y-m-d H:i:s');
        $logMessage = "[{$date}] ERROR: {$message}\n";
        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
    }
}
