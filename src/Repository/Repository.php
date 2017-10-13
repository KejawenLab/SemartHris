<?php

namespace KejawenLab\Application\SemartHris\Repository;

use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
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
}
