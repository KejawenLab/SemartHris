<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Security\Authorization;

use KejawenLab\Semart\Skeleton\Entity\Group;
use KejawenLab\Semart\Skeleton\Entity\Menu;
use KejawenLab\Semart\Skeleton\Entity\Role;
use KejawenLab\Semart\Skeleton\Entity\User;
use KejawenLab\Semart\Skeleton\Security\Service\RoleService;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class PermissionVoter extends Voter
{
    private $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    protected function supports($attribute, $subject): bool
    {
        if ($subject instanceof Menu) {
            return true;
        }

        return false;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        $group = $user->getGroup();
        if (!$group instanceof Group) {
            return false;
        }

        $role = $this->roleService->getRole($group, $subject);
        if (!$role instanceof Role) {
            return false;
        }

        switch ($attribute) {
            case Permission::ADD:
                return $role->isAddable();
                break;
            case Permission::EDIT:
                return $role->isEditable();
                break;
            case Permission::VIEW:
                return $role->isViewable();
                break;
            case Permission::DELETE:
                return $role->isDeletable();
                break;
        }

        throw new \LogicException('This code should not be reached!');
    }
}
