<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Salary\Service;

use KejawenLab\Application\SemartHris\Component\Encryptor\Encryptor;
use KejawenLab\Application\SemartHris\Component\Salary\Model\BenefitHistoryInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\BenefitRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ChangeBenefit
{
    /**
     * @var BenefitRepositoryInterface
     */
    private $benefitRepository;

    /**
     * @var Encryptor
     */
    private $encryptor;

    /**
     * @param BenefitRepositoryInterface $benefitRepository
     * @param Encryptor                  $encryptor
     */
    public function __construct(BenefitRepositoryInterface $benefitRepository, Encryptor $encryptor)
    {
        $this->benefitRepository = $benefitRepository;
        $this->encryptor = $encryptor;
    }

    /**
     * @param BenefitHistoryInterface $benefitHistory
     */
    public function apply(BenefitHistoryInterface $benefitHistory): void
    {
        $oldBenefit = $this->benefitRepository->findByEmployeeAndComponent($benefitHistory->getEmployee(), $benefitHistory->getComponent());
        if (!$oldBenefit) {
            throw new \InvalidArgumentException(sprintf('Employee %s doesn\'t has %s benefit', $benefitHistory->getEmployee(), $benefitHistory->getComponent()));
        }

        $benefitHistory->setOldBenefitValue($this->encryptor->decrypt($oldBenefit->getBenefitValue(), $oldBenefit->getBenefitKey()));
        $oldBenefit->setBenefitValue($benefitHistory->getNewBenefitValue());

        $this->benefitRepository->update($oldBenefit);
    }
}
