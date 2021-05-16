<?php


namespace Framework\lib;


class Cipher
{
    private static $instance;

    private $key;
    private $hmackey;
    private $algorithm;
    private $hmacAlgorithm;
    private $initializationVectorLength;
    private $initializationVector;
    private $options = 0;

    public function __construct()
    {
        $this->key = CIPHER_KEY;
        $this->hmackey = HMAC_KEY;
        $this->algorithm = CIPHER_ALGORITHM;
        $this->hmacAlgorithm = HMAC_ALGORITHM;
        $this->initializationVectorLength = openssl_cipher_iv_length($this->algorithm);
        $this->initializationVector = openssl_random_pseudo_bytes($this->initializationVectorLength);

        self::$instance = $this;
    }

    public static function Instance(): Cipher
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function Encrypt($text)
    {
        if ((in_array($this->algorithm, openssl_get_cipher_methods())) &&
            ($text !== '') && ($this->key != '')
        ) {
            $text = serialize($text);
            $ciphered = $this->initializationVector . openssl_encrypt(
                $text, $this->algorithm, $this->key, $this->options, $this->initializationVector);
            return base64_encode($ciphered);
        } else {
            return false;
        }
    }

    public function Decrypt($cipheredText)
    {
        if ((in_array($this->algorithm, openssl_get_cipher_methods())) &&
            ($cipheredText !== '') &&
            ($this->key != '')
        ) {
            $cipheredText = base64_decode($cipheredText);
            $text = openssl_decrypt(substr($cipheredText, $this->initializationVectorLength),
                $this->algorithm, $this->key, $this->options,
                substr($cipheredText, 0, $this->initializationVectorLength));
            return unserialize($text);
        } else {
            return false;
        }
    }

    public function Hash($text)
    {
        if (($this->hmacAlgorithm !== '') && ($this->hmackey !== '')) {
            return hash_hmac($this->hmacAlgorithm, $text, $this->hmackey);
        }
    }
}