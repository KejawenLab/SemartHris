<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Address\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface Addressable
{
    /**
     * @return AddressInterface|null
     */
    public function getAddress(): ? AddressInterface;

    /**
     * @param AddressInterface|null $address
     */
    public function setAddress(?AddressInterface $address): void;
}
