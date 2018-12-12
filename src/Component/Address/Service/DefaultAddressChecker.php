<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Address\Service;

use Doctrine\Common\Util\ClassUtils;
use KejawenLab\Application\SemartHris\Component\Address\Model\AddressInterface;
use KejawenLab\Application\SemartHris\Component\Address\Repository\AddressRepositoryFactory;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class DefaultAddressChecker
{
    /**
     * @var AddressRepositoryFactory
     */
    private $addressRepositoryFactory;

    /**
     * @param AddressRepositoryFactory $addressRepositoryFactory
     */
    public function __construct(AddressRepositoryFactory $addressRepositoryFactory)
    {
        $this->addressRepositoryFactory = $addressRepositoryFactory;
    }

    /**
     * @param AddressInterface $address
     */
    public function unsetDefaultExcept(AddressInterface $address): void
    {
        if (!$address->isDefaultAddress()) {
            return;
        }

        $realClass = ClassUtils::getRealClass(get_class($address));
        $repository = $this->addressRepositoryFactory->getRepositoryFor($realClass);
        $repository->unsetDefaultExcept($address);

        $addressable = $address->getAddressable();
        if ($addressable) {
            $addressable->setAddress($address);
            $repository->apply($addressable);
        }
    }

    /**
     * @param AddressInterface $address
     */
    public function setRandomDefault(AddressInterface $address): void
    {
        $realClass = ClassUtils::getRealClass(get_class($address));
        $repository = $this->addressRepositoryFactory->getRepositoryFor($realClass);
        $newDefault = $repository->setRandomDefault();

        $addressable = $address->getAddressable();
        $addressable->setAddress($newDefault);

        $repository->apply($addressable);
    }
}
