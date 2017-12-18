<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Reason\Repository;

use KejawenLab\Application\SemartHris\Component\Reason\Model\ReasonInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface ReasonRepositoryInterface
{
    /**
     * @param string $id
     *
     * @return ReasonInterface|null
     */
    public function find(?string $id): ? ReasonInterface;

    /**
     * @param string $type
     *
     * @return ReasonInterface[]
     */
    public function findByType(string $type): array;

    /**
     * @param string $code
     *
     * @return ReasonInterface|null
     */
    public function findAbsentReasonByCode(string $code): ? ReasonInterface;
}
