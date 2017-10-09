<?php

namespace Persona\Hris\Component\Education\Model;

use Persona\Hris\Component\Address\Model\AddressInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@personahris.com>
 */
interface EducationalInstituteInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return AddressInterface[]
     */
    public function getAddresses(): array;
}
