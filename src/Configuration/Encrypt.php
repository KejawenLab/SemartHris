<?php

namespace KejawenLab\Application\SemartHris\Configuration;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
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
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        if (isset($data['properties'])) {
            $this->properties = $data['properties'];
        }
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties ?: [];
    }
}
