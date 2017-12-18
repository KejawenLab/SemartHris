<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Validator;

use KejawenLab\Application\SemartHris\Component\Attendance\Model\AttendanceInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Service\ValidateAttendance;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ValidAttendanceValidator extends ConstraintValidator
{
    /**
     * @param mixed      $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof AttendanceInterface) {
            throw new ValidatorException(sprintf('Your class must implements "%s" interface', AttendanceInterface::class));
        }

        if (!ValidateAttendance::validate($value)) {
            $this->context->buildViolation($constraint->message)->atPath('attendance')->addViolation();
        }
    }
}
