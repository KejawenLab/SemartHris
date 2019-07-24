<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Pagination;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use KejawenLab\Semart\Skeleton\Setting\SettingService;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class Paginator
{
    public const PER_PAGE = 9;

    public const ROOT_ALIAS = 'o';

    private $doctrine;

    private $paginator;

    private $request;

    private $eventDispatcher;

    private $settingService;

    public function __construct(
        ObjectManager $doctrine,
        PaginatorInterface $paginator,
        RequestStack $requestStack,
        EventDispatcherInterface $eventDispatcher,
        SettingService $settingService
    ) {
        $this->doctrine = $doctrine;
        $this->paginator = $paginator;
        $this->request = $requestStack->getCurrentRequest();
        $this->eventDispatcher = $eventDispatcher;
        $this->settingService = $settingService;
    }

    public function paginate(string $entityClass, int $page = 1): PaginationInterface
    {
        $limit = (int) $this->settingService->getValue('PER_PAGE') ?: self::PER_PAGE;

        /** @var EntityRepository $repository */
        $repository = $this->doctrine->getRepository($entityClass);
        $queryBuilder = $repository->createQueryBuilder(self::ROOT_ALIAS);

        $event = new PaginationEvent();
        $event->setQueryBuilder($queryBuilder);
        $event->setRequest($this->request);
        $event->setEntityClass($entityClass);
        $event->addJoinAlias('root', self::ROOT_ALIAS);

        $this->eventDispatcher->dispatch($event);

        return $this->paginator->paginate($queryBuilder, $page, $limit);
    }
}
