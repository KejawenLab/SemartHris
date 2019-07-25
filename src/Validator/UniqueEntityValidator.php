<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Validator;

use Doctrine\Common\Inflector\Inflector;
use KejawenLab\Semart\Skeleton\Repository\Repository;
use KejawenLab\Semart\Skeleton\Repository\RepositoryFactory;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class UniqueEntityValidator extends ConstraintValidator
{
    private $repositoryFactory;

    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->repositoryFactory = $repositoryFactory;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueEntity) {
            throw new UnexpectedTypeException($constraint, UniqueEntity::class);
        }

        $repositry = $this->repositoryFactory->getRepository($constraint->getRepositoryClass());
        if (!$repositry instanceof Repository) {
            throw new UnexpectedTypeException($constraint, UniqueEntity::class);
        }

        $fields = [];
        foreach ($constraint->getFields() as $field) {
            $method = Inflector::camelize(sprintf('get_%s', $field));
            $fields[$field] = $value->{$method}();
        }

        $count = 0;
        $object = $repositry->findUniqueBy($fields);
        if (!$object) {
            return;
        }

        $propertyAccess = new PropertyAccessor();
        foreach ($constraint->getFields() as $field) {
            if ($propertyAccess->getValue($object, $field) === $propertyAccess->getValue($value, $field) && $value->getId() !== $object->getId()) {
                ++$count;
            }
        }

        if ($count) {
            $this->context->buildViolation('label.crud.non_unique_or_deleted')
                ->atPath($constraint->getFields()[0])
                ->addViolation()
            ;
        }
    }
}
