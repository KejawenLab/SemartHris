<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Validator;

use KejawenLab\Application\SemartHris\Component\Job\Model\MutationInterface;
use KejawenLab\Application\SemartHris\Component\Job\Service\ValidateMutation;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ValidMutationValidator extends ConstraintValidator
{
    /**
     * @param mixed      $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof MutationInterface) {
            throw new ValidatorException(sprintf('Your class must implements "%s" interface', MutationInterface::class));
        }

        if (!ValidateMutation::validate($value)) {
            $this->context->buildViolation($constraint->message)->atPath('mutation')->addViolation();
        }
    }
}
