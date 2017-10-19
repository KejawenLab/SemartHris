<?php

namespace KejawenLab\Application\SemartHris\Component\Job\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface PlacementInterface extends CareerHistoryInterface
{
    /**
     * @return bool
     */
    public function isActive(): bool;
}
