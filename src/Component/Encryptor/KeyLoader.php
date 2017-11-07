<?php

namespace KejawenLab\Application\SemartHris\Component\Encryptor;

use KejawenLab\Application\SemartHris\Kernel;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class KeyLoader
{
    const KEY_PHARSE = Kernel::SEMART_APP_CANONICAL;

    /**
     * @var string
     */
    private $publicKeyPath;

    /**
     * @var string
     */
    private $privateKeyPath;

    /**
     * @param string $keyDir
     */
    public function __construct(string $keyDir)
    {
        $this->publicKeyPath = sprintf('%s/public.pem', $keyDir);
        $this->privateKeyPath = sprintf('%s/private.pem', $keyDir);
    }

    /**
     * @return resource
     */
    public function getPublicKey()
    {
        return openssl_pkey_get_public($this->getKey($this->publicKeyPath));
    }

    /**
     * @return bool|resource
     */
    public function getPrivateKey()
    {
        return openssl_pkey_get_private($this->getKey($this->privateKeyPath), self::KEY_PHARSE);
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    private function validate(string $path): bool
    {
        if (is_file($path) && is_readable($path)) {
            return true;
        }

        throw new \RuntimeException(sprintf('%s does not exist or is not readable', $path));
    }

    /**
     * @param string $path
     *
     * @return string
     */
    private function getKey(string $path): string
    {
        $this->validate($path);

        return file_get_contents($path);
    }
}
