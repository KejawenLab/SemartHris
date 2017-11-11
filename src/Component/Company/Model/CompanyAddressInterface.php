<?php

namespace KejawenLab\Application\SemartHris\Component\Company\Model;

use KejawenLab\Application\SemartHris\Component\Address\Model\AddressInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface CompanyAddressInterface extends AddressInterface
{
    /**
     * @return null|CompanyInterface
     */
    public function getCompany(): ? CompanyInterface;

    /**
     * @param CompanyInterface|null $company
     */
    public function setCompany(?CompanyInterface $company): void;
}
