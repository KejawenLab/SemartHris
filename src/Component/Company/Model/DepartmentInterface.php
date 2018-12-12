<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Company\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface DepartmentInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return null|DepartmentInterface
     */
    public function getParent(): ? self;

    /**
     * @param DepartmentInterface|null $department
     */
    public function setParent(?self $department): void;

    /**
     * @return string
     */
    public function getCode(): string;

    /**
     * @return string
     */
    public function getName(): string;
}
