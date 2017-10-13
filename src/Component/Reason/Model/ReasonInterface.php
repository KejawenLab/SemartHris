<?php

namespace KejawenLab\Application\SemartHris\Component\Reason\Model;

use KejawenLab\Application\SemartHris\Component\Reason\ReasonType;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface ReasonInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getCode(): string;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     *
     * @see ReasonType
     */
    public function getType(): string;
}
