<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Entity;

use KejawenLab\Semart\Skeleton\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class UserTest extends TestCase
{
    public function testObject()
    {
        $this->assertEquals(User::class, \get_class(new User()));
    }
}
