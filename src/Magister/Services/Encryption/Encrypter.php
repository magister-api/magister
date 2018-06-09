<?php

namespace Magister\Services\Encryption;

use Magister\Services\Contracts\Encryption\DecryptException;
use Magister\Services\Contracts\Encryption\Encrypter as EncrypterContract;

/**
 * Class Encrypter.
 */
class Encrypter implements EncrypterContract
{
    /**
     * The encryption key.
     *
     * @var string
     */
    protected $key;

    /**
     * The algorithm used for encryption.
     *
     * @var string
     */
    protected $cipher = 'AES-128-CBC';

    /**
     * The mode used for encryption.
     *
     * @var string
     */
    protected $mode = 'cbc';

    /**
     * The block size of the cipher.
     *
     * @var int
     */
    protected $block = 32;

    /**
     * Create a new encrypter instance.
     *
     * @param string $key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * Encrypt the given value.
     *
     * @param string $value
     *
     * @return string
     */
    public function encrypt($value)
    {
        $iv = random_bytes(openssl_cipher_iv_length($this->cipher));

        $value = openssl_encrypt($value, $this->cipher, $this->key, 0, $iv);

        $mac = $this->hash($iv = base64_encode($iv), $value);

        return base64_encode(json_encode(compact('iv', 'value', 'mac')));
    }

    /**
     * Decrypt the given value.
     *
     * @param string $payload
     *
     * @return string
     */
    public function decrypt($payload)
    {
        $payload = $this->getJsonPayload($payload);

        $value = base64_decode($payload['value']);

        $iv = base64_decode($payload['iv']);

        $decryptedValue = openssl_decrypt($value, $this->cipher, $this->key, 0, $iv);

        return unserialize($decryptedValue);
    }

    /**
     * Generate a new key
     * @return string
     */
    public function generateKey()
    {
        return random_bytes(16);
    }

    /**
     * Get the JSON array from the given payload.
     *
     * @param string $payload
     *
     * @throws \Magister\Services\Contracts\Encryption\DecryptException
     *
     * @return array
     */
    protected function getJsonPayload($payload)
    {
        $payload = json_decode(base64_decode($payload), true);

        if (!$payload || $this->invalidPayload($payload)) {
            throw new DecryptException('Invalid data.');
        }

        if (!$this->validMac($payload)) {
            throw new DecryptException('MAC is invalid.');
        }

        return $payload;
    }

    /**
     * Determine if the MAC for the given payload is valid.
     *
     * @param array $payload
     *
     * @return bool
     */
    protected function validMac(array $payload)
    {
        return $payload['mac'] === $this->hash($payload['iv'], $payload['value']);
    }

    /**
     * Create a MAC for the given value.
     *
     * @param string $iv
     * @param string $value
     *
     * @return string
     */
    protected function hash($iv, $value)
    {
        return hash_hmac('sha256', $iv.$value, $this->key);
    }

    /**
     * Verify that the encryption payload is valid.
     *
     * @param array|mixed $data
     *
     * @return bool
     */
    protected function invalidPayload($data)
    {
        return !is_array($data) || !isset($data['iv']) || !isset($data['value']) || !isset($data['mac']);
    }

    /**
     * Set the encryption key.
     *
     * @param string $key
     *
     * @return void
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Set the encryption cipher.
     *
     * @param string $cipher
     *
     * @return void
     */
    public function setCipher($cipher)
    {
        $this->cipher = $cipher;
    }

    /**
     * Set the encryption mode.
     *
     * @param string $mode
     *
     * @return void
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }
}
