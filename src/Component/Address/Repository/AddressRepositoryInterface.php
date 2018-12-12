<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Address\Repository;

use KejawenLab\Application\SemartHris\Component\Address\Model\Addressable;
use KejawenLab\Application\SemartHris\Component\Address\Model\AddressInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
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
    public function apply(Addressable $address): void;

    /**
     * @return AddressInterface
     */
    public function setRandomDefault(): AddressInterface;

    /**
     * @return string
     */
    public function getAddressClass(): string;
}
