<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Component\Company;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class JobTitleExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('level_to_text', [$this, 'convertToText']),
        ];
    }

    public function convertToText(int $level): ?string
    {
        $levels = JobTitleService::getLevels();
        if (!array_key_exists($level, $levels)) {
            return null;
        }

        return $levels[$level];
    }
}
