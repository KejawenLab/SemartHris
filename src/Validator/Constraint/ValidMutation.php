<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Validator\Constraint;

use KejawenLab\Application\SemartHris\Validator\ValidMutationValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ValidMutation extends Constraint
{
    /**
     * @var string
     */
    public $message = 'semarthris.same_job';

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
        return ValidMutationValidator::class;
    }
}
