<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Repository;

use KejawenLab\Semart\Skeleton\Entity\Setting;
use KejawenLab\Semart\Skeleton\Pagination\Paginator;
use KejawenLab\Semart\Skeleton\Repository\Repository;
use KejawenLab\Semart\Skeleton\Repository\SettingRepository;
use KejawenLab\Semart\Skeleton\Tests\TestCase\DatabaseTestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SettingRepositoryTest extends DatabaseTestCase
{
    public function testRepository()
    {
        static::bootKernel();

        $repository = new SettingRepository(static::$container->get('doctrine'));

        $this->assertInstanceOf(Repository::class, $repository);

        /** @var Setting $setting */
        $setting = $repository->findOneBy(['parameter' => 'PER_PAGE']);

        $this->assertEquals(Paginator::PER_PAGE, $setting->getValue());
        $this->assertNull($repository->findOneBy(['parameter' => static::NOT_FOUND]));
    }
}
