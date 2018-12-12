<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Repository;

use Doctrine\ORM\EntityManagerInterface;
use KejawenLab\Application\SemartHris\Util\UuidUtil;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
abstract class Repository
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var string
     */
    protected $entityClass;

    /**
     * @param EntityManagerInterface $entityManager
     * @param string                 $entityClass
     */
    public function initialize(EntityManagerInterface $entityManager, string $entityClass)
    {
        $this->entityManager = $entityManager;
        $this->entityClass = $entityClass;
    }

    /**
     * @param null|string $id
     *
     * @return mixed
     */
    protected function doFind(?string $id)
    {
        if (!$id || !UuidUtil::isValid($id)) {
            return null;
        }

        return $this->entityManager->getRepository($this->entityClass)->find($id);
    }
}
