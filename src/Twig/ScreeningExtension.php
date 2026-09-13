<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Twig;

use KejawenLab\Application\SemartHris\Component\Screening\PhoneMasker;
use KejawenLab\Application\SemartHris\Component\Screening\ScreeningOutcome;
use KejawenLab\Application\SemartHris\Component\Screening\ScreeningStatus;
use KejawenLab\Application\SemartHris\Component\Screening\Service\ValidateScreeningOutcome;
use KejawenLab\Application\SemartHris\Component\Screening\Service\ValidateScreeningStatus;

/**
 * Phone masking and screening status badges for list views.
 * Ported from HireCall ScreeningStatusBadge component.
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ScreeningExtension extends \Twig_Extension
{
    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('semart_screening_badge', [$this, 'renderBadge'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return [
            new \Twig_SimpleFilter('semart_phone_mask', [$this, 'maskPhone']),
        ];
    }

    /**
     * @param null|string $phone
     *
     * @return null|string
     */
    public function maskPhone(?string $phone): ?string
    {
        return PhoneMasker::mask($phone);
    }

    /**
     * Status is never color-only, the label is always printed.
     *
     * @param null|string $value status or outcome code
     * @param string      $kind  status|outcome
     *
     * @return string
     */
    public function renderBadge(?string $value, string $kind = 'status'): string
    {
        if (null === $value || '' === $value) {
            return '<span class="label label-default">-</span>';
        }

        if ('outcome' === $kind) {
            $label = ValidateScreeningOutcome::convertToText($value);
            if ('' === $label) {
                $label = $value;
            }
            $class = $this->outcomeClass($value);

            return sprintf('<span class="label %s">%s</span>', $class, htmlspecialchars($label));
        }

        $label = ValidateScreeningStatus::convertToText($value);
        if ('' === $label) {
            $label = $value;
        }
        $class = $this->statusClass($value);

        return sprintf('<span class="label %s">%s</span>', $class, htmlspecialchars($label));
    }

    /**
     * @param string $status
     *
     * @return string Bootstrap label class
     */
    private function statusClass(string $status): string
    {
        switch (strtolower($status)) {
            case ScreeningStatus::COMPLETED:
                return 'label-success';
            case ScreeningStatus::CALLING:
                return 'label-primary';
            case ScreeningStatus::QUEUED:
                return 'label-warning';
            case ScreeningStatus::NOT_ANSWERED:
            case ScreeningStatus::FAILED:
                return 'label-danger';
            default:
                return 'label-default';
        }
    }

    /**
     * @param string $outcome
     *
     * @return string Bootstrap label class
     */
    private function outcomeClass(string $outcome): string
    {
        switch ($outcome) {
            case ScreeningOutcome::QUALIFIED:
                return 'label-success';
            case ScreeningOutcome::MAYBE:
                return 'label-warning';
            case ScreeningOutcome::NOT_FIT:
                return 'label-danger';
            default:
                return 'label-default';
        }
    }
}
