<?php

namespace Persona\Hris\Component\Company\Model;

use Persona\Hris\Component\Address\Model\AddressInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@personahris.com>
 */
interface CompanyInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return null|CompanyInterface
     */
    public function getParent(): ? CompanyInterface;

    /**
     * @param CompanyInterface $company
     */
    public function setParent(CompanyInterface $company = null): void;

    /**
     * @return string
     */
    public function getCode(): string;

    /**
     * @return \DateTime
     */
    public function getBirthDay(): \DateTime;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return AddressInterface[]
     */
    public function getAddresses(): array;

    /**
     * @return string
     */
    public function getEmail(): string;

    /**
     * @return string
     */
    public function getTaxNumber(): string;
}
