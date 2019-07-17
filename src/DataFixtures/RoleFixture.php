<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use KejawenLab\Semart\Skeleton\Entity\Role;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RoleFixture extends Fixture implements DependentFixtureInterface
{
    protected function createNew()
    {
        return new Role();
    }

    protected function getReferenceKey(): string
    {
        return 'role';
    }

    public function getDependencies()
    {
        return [
            GroupFixture::class,
            MenuFixture::class,
        ];
    }
}
