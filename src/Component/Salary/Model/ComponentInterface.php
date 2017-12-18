<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Salary\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
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
     *
     * @see StateType
     */
    public function getState(): string;

    /**
     * @return bool
     */
    public function isFixed(): bool;
}
