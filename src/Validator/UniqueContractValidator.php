<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Validator;

use KejawenLab\Application\SemartHris\Component\Contract\Model\Contractable;
use KejawenLab\Application\SemartHris\Component\Contract\Service\CheckContract;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class UniqueContractValidator extends ConstraintValidator
{
    /**
     * @var CheckContract
     */
    private $checkContractService;

    /**
     * @param CheckContract $service
     */
    public function __construct(CheckContract $service)
    {
        $this->checkContractService = $service;
    }

    /**
     * @param mixed      $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof Contractable) {
            throw new ValidatorException(sprintf('Your class must implements "%s" interface', Contractable::class));
        }

        if ($this->checkContractService->isAlreadyUsedContract($value)) {
            $this->context->buildViolation($constraint->message)->atPath('contract')->addViolation();
        }
    }
}
