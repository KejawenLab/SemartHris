<?php

namespace Persona\Hris\Component\Address\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@personahris.com>
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
    public function getPhoneNumber(): string;

    /**
     * @return string
     */
    public function getFaxNumber(): string;

    /**
     * @return string
     */
    public function getPostalCode(): string;

    /**
     * @return bool
     */
    public function isDefault(): bool;
}
