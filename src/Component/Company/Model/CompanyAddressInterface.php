<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Company\Model;

use KejawenLab\Application\SemartHris\Component\Address\Model\AddressInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
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
