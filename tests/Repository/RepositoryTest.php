<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Repository;

use KejawenLab\Semart\Skeleton\Repository\Repository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RepositoryTest extends KernelTestCase
{
    public function testRepository()
    {
        $repositoryMock = $this->getMockBuilder(Repository::class)->disableOriginalConstructor()->getMock();
        $repositoryMock
            ->expects($this->once())
            ->method('find')
            ->withAnyParameters()
            ->willReturn(null)
        ;

        $this->assertNull($repositoryMock->find('1'));
    }
}
