<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Entity;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\Event;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class EntityEvent extends Event
{
    private $manager;

    private $entity;

    public function __construct(ObjectManager $entityManager, object $entity)
    {
        $this->manager = $entityManager;
        $this->entity = $entity;
    }

    public function getManager(): ObjectManager
    {
        return $this->manager;
    }

    public function getEntity(): object
    {
        return $this->entity;
    }
}
