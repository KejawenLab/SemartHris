<?php

namespace Tests\KejawenLab\Application\SemartHris\Component\Address\Repository;

use KejawenLab\Application\SemartHris\Component\Address\Model\Addressable;
use KejawenLab\Application\SemartHris\Component\Address\Model\AddressInterface;
use KejawenLab\Application\SemartHris\Component\Address\Repository\AddressRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class AddressRepositoryStub implements AddressRepositoryInterface
{
    /**
     * @param AddressInterface $address
     */
    public function unsetDefaultExcept(AddressInterface $address): void
    {
        // TODO: Implement unsetDefaultExcept() method.
    }

    /**
     * @param Addressable $address
     */
    public function apply(Addressable $address): void
    {
        // TODO: Implement apply() method.
    }

    /**
     * @return AddressInterface
     */
    public function setRandomDefault(): AddressInterface
    {
        // TODO: Implement setRandomDefault() method.
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return self::class;
    }
}
