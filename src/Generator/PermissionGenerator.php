<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Generator;

use Doctrine\Common\Inflector\Inflector;
use KejawenLab\Semart\Skeleton\Entity\Menu;
use KejawenLab\Semart\Skeleton\Menu\MenuService;
use KejawenLab\Semart\Skeleton\Security\Service\GroupService;
use KejawenLab\Semart\Skeleton\Security\Service\RoleService;
use PHLAK\Twine\Str;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class PermissionGenerator extends AbstractGenerator
{
    private $groupService;

    private $menuService;

    private $roleService;

    public function __construct(GroupService $groupService, MenuService $menuService, RoleService $roleService)
    {
        $this->groupService = $groupService;
        $this->menuService = $menuService;
        $this->roleService = $roleService;
    }

    public function generate(\ReflectionClass $entityClass): void
    {
        $menuCode = Str::make($entityClass->getShortName())->uppercase()->__toString();

        $menu = new Menu();
        $menu->setCode($menuCode);
        $menu->setName($menuCode);
        $menu->setRouteName(sprintf('%s_index', Inflector::tableize(Inflector::pluralize($entityClass->getShortName()))));

        $this->menuService->addMenu($menu);
        $this->roleService->assignToMenu($menu);

        if ($role = $this->roleService->getRole($this->groupService->getSuperAdmin(), $menu)) {
            $role->setAddable(true);
            $role->setEditable(true);
            $role->setViewable(true);
            $role->setDeletable(true);

            $this->roleService->updateRole($role);
        }
    }

    public function getPriority(): int
    {
        return 255;
    }
}
