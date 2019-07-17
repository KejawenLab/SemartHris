<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Security\Authorization;

/**
 * @Annotation()
 * @Target({"CLASS", "METHOD"})
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class Permission
{
    public const ADD = 'add';
    public const EDIT = 'edit';
    public const VIEW = 'view';
    public const DELETE = 'delete';

    private $menu;

    private $actions = [];

    private $ownership = false;

    public function __construct(array $configs = [])
    {
        if (isset($configs['menu']) && \is_string($configs['menu'])) {
            $this->menu = $configs['menu'];
        }

        if (isset($configs['actions'])) {
            if (\is_string($configs['actions'])) {
                $this->actions = (array) $configs['actions'];
            }

            if (\is_array($configs['actions'])) {
                $this->actions = $configs['actions'];
            }
        }

        if (isset($configs['ownership']) && \is_bool($configs['ownership'])) {
            $this->ownership = $configs['ownership'];
        }
    }

    public function getMenu(): string
    {
        return (string) $this->menu;
    }

    public function getActions(): array
    {
        return $this->actions;
    }

    public function isOwnership()
    {
        return (bool) $this->ownership;
    }
}
