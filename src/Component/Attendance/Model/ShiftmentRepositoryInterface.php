<?php

namespace KejawenLab\Application\SemartHris\Component\Attendance\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface ShiftmentRepositoryInterface
{
    /**
     * @return ShiftmentInterface[]
     */
    public function findAll(): array;
}
