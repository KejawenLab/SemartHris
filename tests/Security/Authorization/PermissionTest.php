<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Security\Authorization;

use KejawenLab\Semart\Skeleton\Security\Authorization\Permission;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class PermissionTest extends TestCase
{
    public function testConfig()
    {
        $permission = new Permission();

        $this->assertEmpty($permission->getActions());
        $this->assertEmpty($permission->getMenu());

        $permission = new Permission([]);

        $this->assertEmpty($permission->getActions());
        $this->assertEmpty($permission->getMenu());

        $permission = new Permission(['invalid' => 'test']);

        $this->assertEmpty($permission->getActions());
        $this->assertEmpty($permission->getMenu());

        $permission = new Permission(['menu' => [], ['actions' => []]]);

        $this->assertEmpty($permission->getActions());
        $this->assertEmpty($permission->getMenu());

        $permission = new Permission(['menu' => 'Test', 'actions' => Permission::ADD]);

        $this->assertEquals('Test', $permission->getMenu());
        $this->assertCount(1, $permission->getActions());
        $this->assertEquals(Permission::ADD, $permission->getActions()[0]);

        $permission = new Permission(['menu' => 'Test', 'actions' => [Permission::ADD]]);

        $this->assertEquals('Test', $permission->getMenu());
        $this->assertCount(1, $permission->getActions());
        $this->assertEquals(Permission::ADD, $permission->getActions()[0]);
    }
}
