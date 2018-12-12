<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Configuration;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 *
 * @Annotation
 *
 * @Target({"CLASS"})
 */
class Encrypt
{
    /**
     * @var string
     */
    private $properties;

    /**
     * @var string
     */
    private $keyStore;

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        if (isset($data['properties'])) {
            if (!is_array($data['properties'])) {
                $data['properties'] = (array) $data['properties'];
            }

            $this->properties = $data['properties'];
        }

        if (isset($data['keyStore'])) {
            $this->keyStore = $data['keyStore'];
        }
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties ?: [];
    }

    /**
     * @return string
     */
    public function getKeyStore(): string
    {
        return (string) $this->keyStore;
    }
}
