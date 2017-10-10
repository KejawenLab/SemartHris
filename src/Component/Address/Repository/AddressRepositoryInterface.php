<?php

namespace Persona\Hris\Component\Address\Repository;

use Persona\Hris\Component\Address\Model\AddressInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@personahris.com>
 */
interface AddressRepositoryInterface
{
    /**
     * @param AddressInterface $address
     */
    public function unsetDefaultExcept(AddressInterface $address): void;

    /**
     * @return string
     */
    public function getEntityClass(): string;
}
