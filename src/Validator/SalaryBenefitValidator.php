<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Validator;

use KejawenLab\Application\SemartHris\Component\Salary\Model\BenefitInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Service\ValidateBenefit;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SalaryBenefitValidator extends ConstraintValidator
{
    /**
     * @var ValidateBenefit
     */
    private $validateBenefitService;

    /**
     * @param ValidateBenefit $validateBenefit
     */
    public function __construct(ValidateBenefit $validateBenefit)
    {
        $this->validateBenefitService = $validateBenefit;
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed      $value      The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof BenefitInterface) {
            throw new ValidatorException(sprintf('Your class must implements "%s" interface', BenefitInterface::class));
        }

        if ($this->validateBenefitService->employeeHasPayroll($value->getEmployee())) {
            $this->context->buildViolation($constraint->message)->atPath('employee')->addViolation();
        }
    }
}
