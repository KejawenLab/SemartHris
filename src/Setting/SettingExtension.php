<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Setting;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SettingExtension extends AbstractExtension
{
    private $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('setting', [$this, 'getSettingValue']),
        ];
    }

    /**
     * @param string $parameter
     *
     * @return string|int|float|null
     */
    public function getSettingValue(string $parameter)
    {
        return $this->settingService->getValue($parameter);
    }
}
