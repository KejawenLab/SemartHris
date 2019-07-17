<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Repository;

use KejawenLab\Semart\Skeleton\Entity\Group;
use KejawenLab\Semart\Skeleton\Repository\GroupRepository;
use KejawenLab\Semart\Skeleton\Repository\Repository;
use KejawenLab\Semart\Skeleton\Tests\TestCase\DatabaseTestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class GroupRepositoryTest extends DatabaseTestCase
{
    public function testRepository()
    {
        static::bootKernel();

        $repository = new GroupRepository(static::$container->get('doctrine'));

        $this->assertInstanceOf(Repository::class, $repository);

        /** @var Group $group */
        $group = $repository->findOneBy(['code' => 'SPRADM']);

        $this->assertEquals('SPRADM', $group->getCode());
        $this->assertNull($repository->findOneBy(['code' => static::NOT_FOUND]));
    }
}
