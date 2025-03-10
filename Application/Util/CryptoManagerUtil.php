<?php
namespace General\EmailSending\Application\Util;

final class CryptoManagerUtil
{
    private static string $key;

    public static function encryptData($data)
    {
        CryptoManagerUtil::$key = $_ENV['CRYPTO_KEY'];

        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-256-CBC'));
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', CryptoManagerUtil::$key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    public static function decryptData($encryptedData)
    {
        $data = base64_decode($encryptedData);
        $ivLength = openssl_cipher_iv_length('AES-256-CBC');
        $iv = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);
        return openssl_decrypt($encrypted, 'AES-256-CBC', CryptoManagerUtil::$key, 0, $iv);
    }
}
