<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Entity;

use KejawenLab\Semart\Skeleton\Entity\Role;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RoleTest extends TestCase
{
    public function testObject()
    {
        $this->assertEquals(Role::class, \get_class(new Role()));
    }
}
