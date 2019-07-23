<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\EventSubscriber;

use Doctrine\Common\Annotations\Reader;
use KejawenLab\Semart\Collection\Collection;
use KejawenLab\Semart\Skeleton\Menu\MenuService;
use KejawenLab\Semart\Skeleton\Security\Authorization\Permission;
use KejawenLab\Semart\Skeleton\Security\Service\OwnershipService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class AuthorizationSubscriber implements EventSubscriberInterface
{
    private $reader;

    private $menuService;

    private $ownershipService;

    private $authorizationChecker;

    public function __construct(Reader $reader, MenuService $menuService, OwnershipService $ownershipService, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->reader = $reader;
        $this->menuService = $menuService;
        $this->ownershipService = $ownershipService;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function authorize(ControllerEvent $event)
    {
        $controllerObject = $event->getController();
        if (!\is_array($controllerObject)) {
            return;
        }

        $controller = new \ReflectionObject($controllerObject[0]);
        $method = $controller->getMethod($controllerObject[1]);

        /** @var Permission|null $classAnnotation */
        $classAnnotation = $this->reader->getClassAnnotation($controller, Permission::class);
        /** @var Permission|null $methodAnnotation */
        $methodAnnotation = $this->reader->getMethodAnnotation($method, Permission::class);

        if ($classAnnotation && $methodAnnotation) {
            $authorize = 0;
            Collection::collect($methodAnnotation->getActions())
                ->each(function ($value) use (&$authorize, $classAnnotation) {
                    if ($this->authorizationChecker->isGranted($value, $this->menuService->getMenuByCode($classAnnotation->getMenu()))) {
                        ++$authorize;
                    }
                })
            ;

            if (0 === $authorize) {
                throw new AccessDeniedException();
            }
        }

        if ($classAnnotation && $classAnnotation->isOwnership()) {
            if (!$this->ownershipService->isOwner($event->getRequest(), $method)) {
                throw new AccessDeniedException();
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => [['authorize']],
        ];
    }
}
