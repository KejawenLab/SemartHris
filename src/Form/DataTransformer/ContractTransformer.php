<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Form\DataTransformer;

use KejawenLab\Application\SemartHris\Component\Contract\Model\ContractInterface;
use KejawenLab\Application\SemartHris\Component\Contract\Repository\ContractRepositoryInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ContractTransformer implements DataTransformerInterface
{
    /**
     * @var ContractRepositoryInterface
     */
    private $contractRepository;

    /**
     * @param ContractRepositoryInterface $contractRepository
     */
    public function __construct(ContractRepositoryInterface $contractRepository)
    {
        $this->contractRepository = $contractRepository;
    }

    /**
     * @param object $contract
     *
     * @return string
     */
    public function transform($contract): string
    {
        if (null === $contract) {
            return '';
        }

        return $contract->getId();
    }

    /**
     * @param string $contractId
     *
     * @return null|ContractInterface
     */
    public function reverseTransform($contractId)
    {
        if (!$contractId) {
            return null;
        }

        $contract = $this->contractRepository->find($contractId);
        if (null === $contract) {
            throw new TransformationFailedException(sprintf('Contract with id "%s" is not exist.', $contractId));
        }

        return $contract;
    }
}
