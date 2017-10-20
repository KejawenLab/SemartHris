<?php

namespace KejawenLab\Application\SemartHris\Validator\Constraint;

use KejawenLab\Application\SemartHris\Validator\UniqueContractValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class UniqueContract extends Constraint
{
    /**
     * @var string
     */
    public $message = 'smarthris.contract.already_used';

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
        return UniqueContractValidator::class;
    }
}
