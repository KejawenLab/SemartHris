<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Education\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface EducationTitleInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getShortName(): string;

    /**
     * @return string
     */
    public function getName(): string;
}
