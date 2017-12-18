<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Employee\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface Superviseable extends EmployeeInterface
{
    /**
     * @return null|Superviseable
     */
    public function getSupervisor(): ? self;

    /**
     * @param Superviseable|null $superviseable
     */
    public function setSupervisor(?self $superviseable): void;
}
