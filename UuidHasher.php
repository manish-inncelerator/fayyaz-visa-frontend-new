<?php

class UUIDHasher
{
    private $key;
    private $expiryTime;

    /**
     * UUIDHasher constructor.
     *
     * @param string $key A secret key for hashing.
     * @param int $expiryTime Expiry time for hashed UUID (in seconds).
     */
    public function __construct($key, $expiryTime = 300)  // Default expiry is 300 seconds (5 minutes)
    {
        $this->key = $key;
        $this->expiryTime = $expiryTime;
    }

    /**
     * Hashes the UUID using HMAC with the key and stores the timestamp in session.
     *
     * @param string $uuid The UUID to hash.
     * @return string The hashed UUID.
     */
    public function hashUUID($uuid)
    {
        // Use HMAC to create a consistent hash with the secret key and UUID
        $hashedUUID = hash_hmac('sha256', $uuid, $this->key);

        // Store the hashed UUID and timestamp in session
        $_SESSION['hashedUUID'] = $hashedUUID;
        $_SESSION['timestamp'] = time();

        return $hashedUUID;
    }

    /**
     * Verifies if the provided UUID matches the hashed UUID and is within the expiry time.
     * Returns 1 for valid UUID and 0 for expired or invalid UUID.
     *
     * @param string $uuid The UUID to verify.
     * @param string $hu The hashed UUID provided by the user.
     * @return int 1 if valid, 0 if expired or invalid.
     */
    public function verifyUUID($uuid, $hu)
    {
        // Ensure session variables exist
        if (!isset($_SESSION['hashedUUID']) || !isset($_SESSION['timestamp'])) {
            return 0; // No hash or timestamp found
        }

        // Check if expired
        if ((time() - $_SESSION['timestamp']) > $this->expiryTime) {
            unset($_SESSION['hashedUUID']);
            unset($_SESSION['timestamp']);
            return 0; // Expired UUID
        }

        // Recalculate the hash and verify
        $calculatedHash = hash_hmac('sha256', $uuid, $this->key);
        return ($calculatedHash === $_SESSION['hashedUUID'] && $hu === $_SESSION['hashedUUID']) ? 1 : 0;
    }
}


// Secret key for HMAC hashing
$key = 'WXG}&5X(-AAlngo#vix)HnR;nm#soxW7j&{^aSk2jjt$2yDn)M';
