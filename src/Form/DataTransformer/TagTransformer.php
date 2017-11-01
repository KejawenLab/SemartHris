<?php

namespace KejawenLab\Application\SemartHris\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class TagTransformer implements DataTransformerInterface
{
    /**
     * @param array $tags
     *
     * @return string
     */
    public function transform($tags): string
    {
        return implode(',', $tags);
    }

    /**
     * @param string $tags
     *
     * @return array
     */
    public function reverseTransform($tags)
    {
        return explode(',', $tags);
    }
}
