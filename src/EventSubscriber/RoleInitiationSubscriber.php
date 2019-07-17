<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\EventSubscriber;

use KejawenLab\Semart\Skeleton\Application;
use KejawenLab\Semart\Skeleton\Entity\EntityEvent;
use KejawenLab\Semart\Skeleton\Entity\Group;
use KejawenLab\Semart\Skeleton\Entity\Menu;
use KejawenLab\Semart\Skeleton\Security\Service\RoleService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RoleInitiationSubscriber implements EventSubscriberInterface
{
    private $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function initiate(EntityEvent $event)
    {
        $entity = $event->getEntity();

        if ($entity instanceof Group && !$entity->getId()) {
            $this->roleService->assignToGroup($entity);
        }

        if ($entity instanceof Menu && !$entity->getId()) {
            $this->roleService->assignToMenu($entity);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            Application::PRE_COMMIT_EVENT => [['initiate']],
        ];
    }
}
