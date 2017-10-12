<?php

namespace KejawenLab\Application\SemarHris\Component\Address\Repository;

use KejawenLab\Application\SemarHris\Component\Address\Model\AddressInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
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
