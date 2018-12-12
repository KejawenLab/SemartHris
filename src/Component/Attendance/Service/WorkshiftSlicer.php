<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Attendance\Service;

use KejawenLab\Application\SemartHris\Component\Attendance\Model\WorkshiftInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Repository\WorkshiftRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class WorkshiftSlicer
{
    /**
     * @var WorkshiftRepositoryInterface
     */
    private $workshiftRepository;

    /**
     * @var string
     */
    private $workShiftClass;

    /**
     * @param WorkshiftRepositoryInterface $repository
     * @param string                       $workShiftClass
     */
    public function __construct(WorkshiftRepositoryInterface $repository, string $workShiftClass)
    {
        $this->workshiftRepository = $repository;
        $this->workShiftClass = $workShiftClass;
    }

    /**
     * @param WorkshiftInterface $firstWorkshift
     * @param WorkshiftInterface $secondWorkshift
     */
    public function slice(WorkshiftInterface $firstWorkshift, WorkshiftInterface $secondWorkshift): void
    {
        if ($firstWorkshift->getEmployee()->getId() !== $secondWorkshift->getEmployee()->getId()) {
            return;
        }

        $firstStart = $firstWorkshift->getStartDate();
        $firstEnd = $firstWorkshift->getEndDate();

        $secondStart = $secondWorkshift->getStartDate();
        $secondEnd = $secondWorkshift->getEndDate();

        if ($firstStart < $secondStart && $secondEnd < $firstEnd) {
            /** @var \DateTime $newFirstEnd */
            $newFirstEnd = clone $secondStart;
            /** @var \DateTime $thirdStart */
            $thirdStart = clone $secondEnd;
            $adjustment = new \DateInterval('P1D');

            $firstWorkshift->setEndDate($newFirstEnd->sub($adjustment));

            /** @var WorkshiftInterface $thirdWorkshift */
            $thirdWorkshift = new $this->workShiftClass();
            $thirdWorkshift->setStartDate($thirdStart->add($adjustment));
            $thirdWorkshift->setEndDate($firstEnd);
            $thirdWorkshift->setShiftment($firstWorkshift->getShiftment());
            $thirdWorkshift->setEmployee($secondWorkshift->getEmployee());
            $thirdWorkshift->setDescription('SLICED BY SYSTEM');

            $this->workshiftRepository->update($thirdWorkshift);
        }
    }
}
