<?php

namespace KejawenLab\Application\SemarHris\Component\Company\Model;

use KejawenLab\Application\SemarHris\Component\Address\Model\AddressInterface;

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
    public function setCompany(CompanyInterface $company = null): void;
}
