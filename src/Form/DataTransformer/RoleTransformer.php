<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RoleTransformer implements DataTransformerInterface
{
    /**
     * @param array $roles
     *
     * @return string
     */
    public function transform($roles): string
    {
        if (is_array($roles) && isset($roles[0])) {
            return $roles[0];
        }

        return '';
    }

    /**
     * @param string $roles
     *
     * @return array
     */
    public function reverseTransform($roles)
    {
        return [$roles];
    }
}
