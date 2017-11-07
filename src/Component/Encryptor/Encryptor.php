<?php

namespace KejawenLab\Application\SemartHris\Component\Encryptor;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class Encryptor
{
    /**
     * @var KeyLoader
     */
    private $keyLoader;

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
        $result = openssl_public_encrypt($plain, $encryptedData, $this->keyLoader->getPublicKey());
        if (!$result) {
            return $plain;
        }

        return $encryptedData;
    }

    /**
     * @param string $encrypted
     *
     * @return mixed
     */
    public function decrypt(string $encrypted)
    {
        $result = openssl_private_decrypt($encrypted, $decryptedData, $this->keyLoader->getPrivateKey());
        if (!$result) {
            return $encrypted;
        }

        return $decryptedData;
    }
}
