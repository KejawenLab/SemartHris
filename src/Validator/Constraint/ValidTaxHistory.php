<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Validator\Constraint;

use KejawenLab\Application\SemartHris\Validator\ValidTaxHistoryValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ValidTaxHistory extends Constraint
{
    /**
     * @var string
     */
    public $message = 'semarthris.same_tax';

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
        return ValidTaxHistoryValidator::class;
    }
}
