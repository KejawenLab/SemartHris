<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\DataFixtures;

use KejawenLab\Semart\Skeleton\Entity\Setting;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SettingFixture extends Fixture
{
    protected function createNew()
    {
        return new Setting();
    }

    protected function getReferenceKey(): string
    {
        return 'setting';
    }
}
