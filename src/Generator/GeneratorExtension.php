<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Generator;

use Doctrine\Common\Inflector\Inflector;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class GeneratorExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('pluralize', [$this, 'pluralize']),
            new TwigFilter('humanize', [$this, 'humanize']),
            new TwigFilter('underscore', [$this, 'underscore']),
            new TwigFilter('dash', [$this, 'dash']),
            new TwigFilter('camelcase', [$this, 'camelcase']),
        ];
    }

    public function pluralize(string $value): string
    {
        return Inflector::pluralize($value);
    }

    public function humanize(string $value): string
    {
        return ucfirst($value);
    }

    public function underscore(string $value): string
    {
        return Inflector::tableize($value);
    }

    public function dash(string $value): string
    {
        return str_replace('_', '-', Inflector::tableize($value));
    }

    public function camelcase(string $value): string
    {
        return Inflector::camelize($value);
    }
}
