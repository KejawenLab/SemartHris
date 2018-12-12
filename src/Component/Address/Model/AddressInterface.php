<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Address\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
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
     * @param string $address
     */
    public function setAddress(string $address): void;

    /**
     * @return null|RegionInterface
     */
    public function getRegion(): ? RegionInterface;

    /**
     * @param RegionInterface|null $region
     */
    public function setRegion(?RegionInterface $region): void;

    /**
     * @return null|CityInterface
     */
    public function getCity(): ? CityInterface;

    /**
     * @param CityInterface|null $city
     */
    public function setCity(?CityInterface $city): void;

    /**
     * @return string
     */
    public function getPostalCode(): string;

    /**
     * @param string $postalCode
     */
    public function setPostalCode(string $postalCode): void;

    /**
     * @return string
     */
    public function getPhoneNumber(): string;

    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber(string $phoneNumber): void;

    /**
     * @return string
     */
    public function getFaxNumber(): string;

    /**
     * @param string $faxNumber
     */
    public function setFaxNumber(string $faxNumber): void;

    /**
     * @return bool
     */
    public function isDefaultAddress(): bool;

    /**
     * @param bool $default
     */
    public function setDefaultAddress(bool $default): void;

    /**
     * @return Addressable|null
     */
    public function getAddressable(): ? Addressable;
}
