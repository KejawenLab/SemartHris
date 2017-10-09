<?php

namespace Persona\Hris\Component\Reason\Model;

use Persona\Hris\Component\Reason\ReasonType;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@personahris.com>
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
