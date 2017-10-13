<?php

namespace KejawenLab\Application\SemartHris\Component\Education\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
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
