<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Validator;

use KejawenLab\Application\SemartHris\Component\Tax\Model\TaxGroupHistoryInterface;
use KejawenLab\Application\SemartHris\Component\Tax\Service\ValidateTaxHistory;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ValidTaxHistoryValidator extends ConstraintValidator
{
    /**
     * @param mixed      $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof TaxGroupHistoryInterface) {
            throw new ValidatorException(sprintf('Your class must implements "%s" interface', TaxGroupHistoryInterface::class));
        }

        if (!ValidateTaxHistory::validate($value)) {
            $this->context->buildViolation($constraint->message)->atPath('newTaxGroup')->addViolation();
            $this->context->buildViolation($constraint->message)->atPath('newRiskRatio')->addViolation();
        }
    }
}
