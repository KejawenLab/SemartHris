<?php

namespace KejawenLab\Application\SemartHris\Component\Address\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface Addressable
{
    /**
     * @return string
     */
    public function getAddress(): string;

    /**
     * @return null|RegionInterface
     */
    public function getRegion(): ? RegionInterface;

    /**
     * @return null|CityInterface
     */
    public function getCity(): ? CityInterface;

    /**
     * @return string
     */
    public function getPostalCode(): string;

    /**
     * @return string
     */
    public function getPhoneNumber(): string;

    /**
     * @return string
     */
    public function getFaxNumber(): string;

    /**
     * @return string
     */
    public function getAddressClass(): string;
}
