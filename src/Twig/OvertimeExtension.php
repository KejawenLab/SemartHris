<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Twig;

use KejawenLab\Application\SemartHris\Component\Employee\Repository\EmployeeRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Overtime\Model\OvertimeInterface;
use KejawenLab\Application\SemartHris\Component\Overtime\Service\OvertimeCalculator;
use KejawenLab\Application\SemartHris\Component\Setting\Service\Setting;
use KejawenLab\Application\SemartHris\Component\Setting\SettingKey;
use KejawenLab\Application\SemartHris\Util\StringUtil;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class OvertimeExtension extends \Twig_Extension
{
    /**
     * @var EmployeeRepositoryInterface
     */
    private $employeeRepository;

    /**
     * @var OvertimeCalculator
     */
    private $overtimeCalculator;

    /**
     * @var Setting
     */
    private $setting;

    /**
     * @var string
     */
    private $overtimeClass;

    /**
     * @param EmployeeRepositoryInterface $employeeRepository
     * @param OvertimeCalculator          $overtimeCalculator
     * @param Setting                     $setting
     * @param string                      $overtimeClass
     */
    public function __construct(
        EmployeeRepositoryInterface $employeeRepository,
        OvertimeCalculator $overtimeCalculator,
        Setting $setting,
        string $overtimeClass
    ) {
        $this->employeeRepository = $employeeRepository;
        $this->overtimeCalculator = $overtimeCalculator;
        $this->setting = $setting;
        $this->overtimeClass = $overtimeClass;
    }

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('semarthris_create_overtime_preview', [$this, 'createOvertimePreview']),
        ];
    }

    /**
     * @param array $preview
     *
     * @return OvertimeInterface
     */
    public function createOvertimePreview(array $preview): OvertimeInterface
    {
        if (!(isset($preview['employee_code']) || isset($preview['date']))) {
            throw new \InvalidArgumentException();
        }

        /* @var OvertimeInterface $overtime */
        if (!$employee = $this->employeeRepository->findByCode(StringUtil::sanitize($preview['employee_code']))) {
            throw new \InvalidArgumentException();
        }

        $overtimeDate = \DateTime::createFromFormat($this->setting->get(SettingKey::DATE_FORMAT), StringUtil::sanitize($preview['date']));
        $overtime = new $this->overtimeClass();
        $overtime->setOvertimeDate($overtimeDate);
        $overtime->setEmployee($employee);

        if (!(isset($preview['check_in']) && $preview['check_in']) || !(isset($preview['check_out']) && $preview['check_out'])) {
            $overtime->setStartHour(\DateTime::createFromFormat('H:i', '00:00'));
            $overtime->setEndHour(\DateTime::createFromFormat('H:i', '00:00'));
        } else {
            $overtime->setStartHour(\DateTime::createFromFormat('H:i', StringUtil::sanitize($preview['check_in'])));
            $overtime->setEndHour(\DateTime::createFromFormat('H:i', StringUtil::sanitize($preview['check_out'])));
        }

        $this->overtimeCalculator->calculate($overtime);

        return $overtime;
    }
}
