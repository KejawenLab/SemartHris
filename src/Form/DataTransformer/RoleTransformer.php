<?php

namespace KejawenLab\Application\SemartHris\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class RoleTransformer implements DataTransformerInterface
{
    /**
     * @param array $roles
     *
     * @return string
     */
    public function transform($roles): string
    {
        if (is_array($roles) && array_key_exists(0, $roles)) {
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
