<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Security\Service;

use KejawenLab\Semart\Collection\Collection;
use KejawenLab\Semart\Skeleton\Contract\Service\ServiceInterface;
use KejawenLab\Semart\Skeleton\Entity\Group;
use KejawenLab\Semart\Skeleton\Entity\Menu;
use KejawenLab\Semart\Skeleton\Entity\Role;
use KejawenLab\Semart\Skeleton\Repository\GroupRepository;
use KejawenLab\Semart\Skeleton\Repository\MenuRepository;
use KejawenLab\Semart\Skeleton\Repository\RoleRepository;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RoleService implements ServiceInterface
{
    private $roleRepository;

    private $menuRepository;

    private $groupRepository;

    public function __construct(RoleRepository $roleRepository, MenuRepository $menuRepository, GroupRepository $groupRepository)
    {
        $roleRepository->setCacheable(true);
        $menuRepository->setCacheable(true);
        $groupRepository->setCacheable(true);

        $this->roleRepository = $roleRepository;
        $this->menuRepository = $menuRepository;
        $this->groupRepository = $groupRepository;
    }

    public function assignToGroup(Group $group): void
    {
        Collection::collect($this->menuRepository->findAll())
            ->each(function ($menu, $key) use ($group) {
                $role = new Role();
                $role->setGroup($group);
                $role->setMenu($menu);

                $this->roleRepository->persist($role);

                if ($key > 0 && 0 === 17 % $key) {
                    $this->roleRepository->commit();
                }
            })
        ;

        $this->roleRepository->commit();
    }

    public function assignToMenu(Menu $menu): void
    {
        Collection::collect($this->groupRepository->findAll())
            ->each(function ($group, $key) use ($menu) {
                /** @var Group $group */
                $role = new Role();
                $role->setGroup($group);
                $role->setMenu($menu);

                $this->roleRepository->persist($role);

                if ($key > 0 && 0 === 17 % $key) {
                    $this->roleRepository->commit();
                }
            })
        ;

        $this->roleRepository->commit();
    }

    public function getRolesByGroup(Group $group, string $query = ''): array
    {
        return $this->roleRepository->findRolesByGroup($group, $query);
    }

    public function getRole(Group $group, Menu $menu): ?Role
    {
        return $this->roleRepository->findRole($group, $menu);
    }

    public function updateRole(Role $role): void
    {
        $this->roleRepository->persist($role);
        $this->roleRepository->commit();
    }

    /**
     * @param string $id
     *
     * @return Role|null
     */
    public function get(string $id): ?object
    {
        return $this->roleRepository->find($id);
    }
}
