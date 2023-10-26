<?php

namespace Services;

class Crypto
{
    private string $key;

    public function __construct($key)
    {
        $this->key = substr(hash('sha256', $key, true), 0, 32);
    }

    public function Encrypt($in): string
    {
        $method = 'aes-256-cbc';
        $ivLen = openssl_cipher_iv_length($method);
        $iv = openssl_random_pseudo_bytes($ivLen);
        $out = $this->Base64UrlEncode($iv . openssl_encrypt($in, $method, $this->key, OPENSSL_RAW_DATA, $iv));
        return $out;
    }

    public function Base64UrlEncode($data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public function Decrypt($in): bool|string
    {
        $in = $this->Base64UrlDecode($in);
        $method = 'aes-256-cbc';
        $ivLen = openssl_cipher_iv_length($method);
        $out = openssl_decrypt(substr($in, $ivLen), $method, $this->key, OPENSSL_RAW_DATA, substr($in, 0, $ivLen));
        return $out;
    }

    public function Base64UrlDecode($data): bool|string
    {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }
}