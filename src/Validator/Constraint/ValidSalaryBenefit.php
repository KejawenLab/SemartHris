<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Validator\Constraint;

use KejawenLab\Application\SemartHris\Validator\SalaryBenefitValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ValidSalaryBenefit extends Constraint
{
    /**
     * @var string
     */
    public $message = 'semarthris.benefit.employee_has_payroll';

    /**
     * @return string
     */
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return SalaryBenefitValidator::class;
    }
}
