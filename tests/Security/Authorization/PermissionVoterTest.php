<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Security\Authorization;

use KejawenLab\Semart\Skeleton\Entity\Group;
use KejawenLab\Semart\Skeleton\Entity\Menu;
use KejawenLab\Semart\Skeleton\Entity\Role;
use KejawenLab\Semart\Skeleton\Entity\User;
use KejawenLab\Semart\Skeleton\Security\Authorization\Permission;
use KejawenLab\Semart\Skeleton\Security\Authorization\PermissionVoter;
use KejawenLab\Semart\Skeleton\Security\Service\RoleService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class PermissionVoterTest extends TestCase
{
    public function testVote()
    {
        $group = new Group();
        $group->setCode(Group::SUPER_ADMINISTRATOR_CODE);

        $user = new User();
        $user->setGroup($group);

        $tokenMock = $this->getMockBuilder(TokenInterface::class)->disableOriginalConstructor()->getMock();
        $tokenMock
            ->expects($this->atLeastOnce())
            ->method('getUser')
            ->willReturn($user)
        ;

        $subject = new Menu();

        $role = new Role();
        $role->setGroup($group);
        $role->setAddable(true);
        $role->setEditable(true);
        $role->setViewable(true);
        $role->setDeletable(false);

        $roleRepositoryMock = $this->getMockBuilder(RoleService::class)->disableOriginalConstructor()->getMock();
        $roleRepositoryMock
            ->expects($this->atLeastOnce())
            ->method('getRole')
            ->with($group, $subject)
            ->willReturn($role)
        ;

        $voter = new PermissionVoter($roleRepositoryMock);

        $this->assertEquals(0, $voter->vote($tokenMock, $group, [Permission::ADD]));
        $this->assertEquals(-1, $voter->vote($tokenMock, $subject, [Permission::DELETE]));
        $this->assertEquals(1, $voter->vote($tokenMock, $subject, [Permission::VIEW]));
    }
}
