<?php

namespace KejawenLab\Application\SemartHris\Component\Address\Repository;

use KejawenLab\Application\SemartHris\Component\Address\Model\Addressable;
use KejawenLab\Application\SemartHris\Component\Address\Model\AddressInterface;

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
     * @param Addressable $address
     */
    public function saveAddress(Addressable $address): void;

    public function setRandomDefault(): void;

    /**
     * @return string
     */
    public function getEntityClass(): string;
}
