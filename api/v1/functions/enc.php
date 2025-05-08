<?php

/**
 * Encrypts data using either Libsodium (XChaCha20-Poly1305) or OpenSSL (AES-256-CBC).
 *
 * @param string $plaintext Data to encrypt.
 * @param string $key       32-byte encryption key.
 *
 * @return string           Base64-encoded encrypted data.
 * @throws Exception        If encryption fails.
 */
function encryptData(string $plaintext, string $key): string {
    if (extension_loaded('sodium')) {
        // Libsodium encryption (XChaCha20-Poly1305)
        if (strlen($key) !== SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_KEYBYTES) {
            throw new Exception("Key must be 32 bytes for Libsodium.");
        }
        $nonce = random_bytes(SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES);
        $ciphertext = sodium_crypto_aead_xchacha20poly1305_ietf_encrypt($plaintext, '', $nonce, $key);
        return base64_encode($nonce . $ciphertext);
    } else {
        // OpenSSL encryption (AES-256-CBC)
        if (strlen($key) !== 32) {
            throw new Exception("Key must be 32 bytes for AES.");
        }
        $iv = random_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $ciphertext = openssl_encrypt($plaintext, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($iv . $ciphertext);
    }
}

/**
 * Decrypts data using either Libsodium or OpenSSL.
 *
 * @param string $encryptedData Base64-encoded encrypted data.
 * @param string $key           32-byte encryption key.
 *
 * @return string               Decrypted plaintext.
 * @throws Exception            If decryption fails.
 */
function decryptData(string $encryptedData, string $key): string {
    $data = base64_decode($encryptedData);
    if ($data === false) {
        throw new Exception("Invalid Base64 encoding.");
    }

    if (extension_loaded('sodium')) {
        // Libsodium decryption
        $nonceLength = SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES;
        $nonce = substr($data, 0, $nonceLength);
        $ciphertext = substr($data, $nonceLength);
        $plaintext = sodium_crypto_aead_xchacha20poly1305_ietf_decrypt($ciphertext, '', $nonce, $key);
        if ($plaintext === false) {
            throw new Exception("Decryption failed (Libsodium).");
        }
        return $plaintext;
    } else {
        // OpenSSL decryption
        $ivLength = openssl_cipher_iv_length('aes-256-cbc');
        $iv = substr($data, 0, $ivLength);
        $ciphertext = substr($data, $ivLength);
        $plaintext = openssl_decrypt($ciphertext, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
        if ($plaintext === false) {
            throw new Exception("Decryption failed (OpenSSL).");
        }
        return $plaintext;
    }
}

/*
// Example usage:
$encryptionKey = random_bytes(32); // Generate a secure key (store this securely)
$data = "Hello, world!";

// Encrypt
$encrypted = encryptData($data, $encryptionKey);
echo "Encrypted: $encrypted\n";

// Decrypt
$decrypted = decryptData($encrypted, $encryptionKey);
echo "Decrypted: $decrypted\n";
*/
?>
