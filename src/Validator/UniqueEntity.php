<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"CLASS"})
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class UniqueEntity extends Constraint
{
    public $fields = [];

    public $repositoryClass;

    public function getRepositoryClass(): ?string
    {
        return $this->repositoryClass;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function validatedBy(): string
    {
        return UniqueEntityValidator::class;
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
