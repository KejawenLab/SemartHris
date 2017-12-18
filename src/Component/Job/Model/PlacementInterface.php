<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Job\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface PlacementInterface extends CareerHistoryInterface, CareerHistoryable
{
    /**
     * @return bool
     */
    public function isActive(): bool;
}
