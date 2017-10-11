<?php

namespace KejawenLab\Application\SemarHris\Component\Company\Model;

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
     * @return \DateTimeInterface
     */
    public function getBirthDay(): ? \DateTimeInterface;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getEmail(): string;

    /**
     * @return string
     */
    public function getTaxNumber(): string;
}
