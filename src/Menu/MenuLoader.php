<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Menu;

use KejawenLab\Semart\Skeleton\Cache\CacheHandler;
use KejawenLab\Semart\Skeleton\Entity\Menu;
use KejawenLab\Semart\Skeleton\Entity\User;
use KejawenLab\Semart\Skeleton\Repository\RoleRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class MenuLoader
{
    private $roleRepository;

    private $tokenStorage;

    private $childMenu;

    private $cacheProvider;

    public function __construct(RoleRepository $roleRepository, TokenStorageInterface $tokenStorage, CacheHandler $cacheProvider)
    {
        $this->roleRepository = $roleRepository;
        $this->tokenStorage = $tokenStorage;
        $this->cacheProvider = $cacheProvider;
    }

    /**
     * @return Menu[]
     */
    public function getParentMenu(): array
    {
        if (!$token = $this->tokenStorage->getToken()) {
            return [];
        }

        /** @var User $user */
        $user = $token->getUser();
        if (!$group = $user->getGroup()) {
            return [];
        }

        $key = md5(sprintf('%s:%s:%s', __CLASS__, __METHOD__, $group->getId()));
        if (!$this->cacheProvider->isCached($key)) {
            $menus = $this->roleRepository->findParentMenuByGroup($group);
            $this->cacheProvider->cache($key, $menus);
        } else {
            $menus = $this->cacheProvider->getItem($key);
        }

        return $menus;
    }

    public function hasChildMenu(Menu $menu): bool
    {
        $this->childMenu = $this->findChildMenu($menu);
        if (empty($this->childMenu)) {
            return false;
        }

        return true;
    }

    /**
     * @param Menu $menu
     *
     * @return Menu[]
     */
    public function getChildMenu(Menu $menu): array
    {
        if (null === $this->childMenu) {
            return $this->findChildMenu($menu);
        }

        $childs = $this->childMenu;
        $this->childMenu = null;

        return $childs;
    }

    private function findChildMenu(Menu $menu): array
    {
        if (!$token = $this->tokenStorage->getToken()) {
            return [];
        }

        /** @var User $user */
        $user = $token->getUser();
        if (!$group = $user->getGroup()) {
            return [];
        }

        $key = md5(sprintf('%s:%s:%s:%s', __CLASS__, __METHOD__, $group->getId(), $menu->getId()));
        if (!$this->cacheProvider->isCached($key)) {
            $menus = $this->roleRepository->findChildMenuByGroupAndMenu($group, $menu);
            $this->cacheProvider->cache($key, $menus);
        } else {
            $menus = $this->cacheProvider->getItem($key);
        }

        return $menus;
    }
}
