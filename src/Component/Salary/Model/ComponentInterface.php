<?php

namespace KejawenLab\Application\SemartHris\Component\Salary\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface ComponentInterface
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
     */
    public function getState(): string;

    /**
     * @return bool
     */
    public function isFixed(): bool;
}
