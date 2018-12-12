<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Address\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface CityInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return null|RegionInterface
     */
    public function getRegion(): ? RegionInterface;

    /**
     * @param RegionInterface|null $region
     */
    public function setRegion(?RegionInterface $region): void;

    /**
     * @return string
     */
    public function getCode(): string;

    /**
     * @return string
     */
    public function getName(): string;
}
