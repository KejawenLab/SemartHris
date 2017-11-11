<?php

namespace KejawenLab\Application\SemartHris\Component\Address\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
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
