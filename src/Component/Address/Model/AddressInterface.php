<?php

namespace KejawenLab\Application\SemarHris\Component\Address\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface AddressInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getAddress(): string;

    /**
     * @return null|RegionInterface
     */
    public function getRegion(): ? RegionInterface;

    /**
     * @param RegionInterface|null $region
     */
    public function setRegion(RegionInterface $region = null): void;

    /**
     * @return null|CityInterface
     */
    public function getCity(): ? CityInterface;

    /**
     * @param CityInterface|null $city
     */
    public function setCity(CityInterface $city = null): void;

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
     * @return bool
     */
    public function isDefaultAddress(): bool;
}
