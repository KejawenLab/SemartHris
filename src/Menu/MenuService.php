<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Menu;

use KejawenLab\Semart\Skeleton\Contract\Service\ServiceInterface;
use KejawenLab\Semart\Skeleton\Entity\Menu;
use KejawenLab\Semart\Skeleton\Repository\MenuRepository;
use PHLAK\Twine\Str;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class MenuService implements ServiceInterface
{
    private $menuRepository;

    public function __construct(MenuRepository $menuRepository)
    {
        $menuRepository->setCacheable(true);
        $this->menuRepository = $menuRepository;
    }

    public function getMenuByCode(string $code): ?Menu
    {
        return $this->menuRepository->findByCode(Str::make($code)->uppercase()->__toString());
    }

    public function addMenu(Menu $menu): void
    {
        $this->menuRepository->commit($menu);
    }

    /**
     * @return Menu[]
     */
    public function getActiveMenus(): array
    {
        return $this->menuRepository->findAll();
    }

    /**
     * @param string $id
     *
     * @return Menu|null
     */
    public function get(string $id): ?object
    {
        return $this->menuRepository->find($id);
    }
}
