<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Encryptor;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class Encryptor
{
    /**
     * @var KeyLoader
     */
    private $keyLoader;

    /**
     * @var string
     */
    private $key;

    /**
     * @param KeyLoader $keyLoader
     */
    public function __construct(KeyLoader $keyLoader)
    {
        $this->keyLoader = $keyLoader;
    }

    /**
     * @param mixed $plain
     *
     * @return string
     */
    public function encrypt($plain): string
    {
        if (!$this->key) {
            $this->key = $this->generateKey();
        }

        $result = openssl_public_encrypt(sprintf('%s#%s', $this->key, $plain), $encryptedData, $this->keyLoader->getPublicKey());
        if (!$result) {
            return $plain;
        }

        return base64_encode(sprintf('%s#%s', $encryptedData, $this->key));
    }

    /**
     * @param string $encrypted
     * @param string $key
     *
     * @return mixed
     */
    public function decrypt(string $encrypted, string $key)
    {
        $result = openssl_private_decrypt(str_replace(sprintf('#%s', $key), '', base64_decode($encrypted)), $decryptedData, $this->keyLoader->getPrivateKey());
        if (!$result) {
            return $encrypted;
        }

        return str_replace(sprintf('%s#', $key), '', $decryptedData);
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return (string) $this->key;
    }

    /**
     * @return string
     */
    private function generateKey(): string
    {
        return sha1(uniqid());
    }
}
