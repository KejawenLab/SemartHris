<?php
/**
 * This file is part of the Semart HRIS Application.
 *
 * (c) Muhamad Surya Iksanudin <surya.kejawen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Component\Attendance;

use KejawenLab\Semart\Skeleton\Component\Contract\Attendance\WorkshiftInterface;
use KejawenLab\Semart\Skeleton\Component\Contract\Attendance\WorkshiftRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
final class WorkshiftSlicer
{
    private $repository;

    private $entityClass;

    public function __construct(WorkshiftRepositoryInterface $workshiftRepository, string $workshiftClass)
    {
        $this->repository = $workshiftRepository;
        $this->entityClass = $workshiftClass;
    }

    public function sliceAndUpdate(WorkshiftInterface $firstWorkshift, WorkshiftInterface $secondWorkshift): void
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
            $thirdWorkshift = new $this->entityClass();
            $thirdWorkshift->setStartDate($thirdStart->add($adjustment));
            $thirdWorkshift->setEndDate($firstEnd);
            $thirdWorkshift->setShiftment($firstWorkshift->getShiftment());
            $thirdWorkshift->setEmployee($secondWorkshift->getEmployee());
            $thirdWorkshift->setDescription('SLICED BY SYSTEM');
            $this->repository->save($thirdWorkshift);
        }
    }
}
