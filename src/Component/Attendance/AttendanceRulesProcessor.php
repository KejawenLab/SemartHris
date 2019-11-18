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

use KejawenLab\Semart\Skeleton\Component\Contract\Attendance\AttendanceInterface;
use KejawenLab\Semart\Skeleton\Component\Contract\Attendance\AttendanceRuleInterface;
use KejawenLab\Semart\Skeleton\Component\Contract\Attendance\RuleNotQualifiedException;
use KejawenLab\Semart\Skeleton\Component\Contract\Employee\EmployeeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
final class AttendanceRulesProcessor implements AttendanceRuleInterface
{
    /**
     * @var AttendanceRuleInterface[]
     */
    private $attendaceRules;

    public function __construct(AttendanceRuleInterface ...$attendaceRules)
    {
        $this->attendaceRules = $attendaceRules;
    }

    public function apply(EmployeeInterface $employee, \DateTimeInterface $attendanceDate): AttendanceInterface
    {
        foreach ($this->attendaceRules as $rule) {
            try {
                return $rule->apply($employee, $attendanceDate);
            } catch (RuleNotQualifiedException $exception) {
                continue;
            }
        }

        throw new RuleNotQualifiedException();
    }
}
