<?php
class PageCache
{
    private $cacheFolder = 'cache/minify/';
    private $cacheTime = 18000; // 5 hours in seconds
    private $monitoredFolders = [__DIR__ . '/components']; // Folders to monitor for changes
    private $currentPage; // Current page file (e.g., home.php)

    public function __construct($currentPage = null)
    {
        // Enable error reporting
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $this->currentPage = $currentPage ?? $_SERVER['SCRIPT_FILENAME']; // Default to the current script
        $this->ensureCacheFolderExists();

        // Ensure session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function ensureCacheFolderExists()
    {
        if (!is_dir($this->cacheFolder)) {
            mkdir($this->cacheFolder, 0777, true);
        }
        if (!is_writable($this->cacheFolder)) {
            die("Cache folder is not writable. Please check permissions.");
        }
    }

    private function getSlug()
    {
        $url = $_SERVER["REQUEST_URI"];
        $slug = trim(parse_url($url, PHP_URL_PATH), '/');
        return preg_replace('/[^a-zA-Z0-9_-]/', '-', $slug);
    }

    public function getCacheFile()
    {
        $slug = $this->getSlug();
        return $this->cacheFolder . $slug . '.html';
    }

    private function getLastModifiedTime($folder)
    {
        $lastModifiedTime = 0;

        // Check if the folder exists
        if (!is_dir($folder)) {
            return $lastModifiedTime; // Return 0 if the folder doesn't exist
        }

        // Iterate over the folder
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($folder, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($files as $file) {
            if ($file->isFile()) {
                $lastModifiedTime = max($lastModifiedTime, $file->getMTime());
            }
        }

        return $lastModifiedTime;
    }

    public function isCacheValid($cacheFile)
    {
        if (!file_exists($cacheFile)) {
            return false;
        }

        $cacheModifiedTime = filemtime($cacheFile);

        // Check if cache is expired based on time
        if (time() - $this->cacheTime > $cacheModifiedTime) {
            return false;
        }

        // Check if the current page has been modified
        if (file_exists($this->currentPage) && filemtime($this->currentPage) > $cacheModifiedTime) {
            return false;
        }

        // Check if any monitored folder has been modified
        foreach ($this->monitoredFolders as $folder) {
            if ($this->getLastModifiedTime($folder) > $cacheModifiedTime) {
                return false;
            }
        }

        return true;
    }

    public function serveCachedPage()
    {
        // Ensure session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $cacheFile = $this->getCacheFile();

        if ($this->isCacheValid($cacheFile)) {
            echo "<!-- Serving HTML file from cache ($cacheFile) -->\n";
            echo file_get_contents($cacheFile);
            exit;
        }

        return false;
    }

    public function startBuffering()
    {
        // Check if headers have already been sent
        if (headers_sent()) {
            die("Headers already sent. Cannot start output buffering.");
        }

        // Start output buffering with gzip compression if supported
        if (!ob_start("ob_gzhandler")) {
            ob_start();
        }
    }

    public function saveCache($content)
    {
        $cacheFile = $this->getCacheFile();
        file_put_contents($cacheFile, $content);
    }
}
