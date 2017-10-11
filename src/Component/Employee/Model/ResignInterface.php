<?php

namespace KejawenLab\Application\SemarHris\Component\Employee\Model;

use KejawenLab\Application\SemarHris\Component\Reason\Model\ReasonInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@personahris.com>
 */
interface ResignInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return null|EmployeeInterface
     */
    public function getEmployee(): ? EmployeeInterface;

    /**
     * @param EmployeeInterface $employee
     */
    public function setEmployee(EmployeeInterface $employee = null): void;

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime;

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date): void;

    /**
     * @return null|ReasonInterface
     */
    public function getReason(): ? ReasonInterface;

    /**
     * @param ReasonInterface $reason
     */
    public function setReason(ReasonInterface $reason = null): void;

    /**
     * @return string
     */
    public function getDescription(): ? string;

    /**
     * @param string $remark
     */
    public function setDescription(string $remark = null): void;
}
