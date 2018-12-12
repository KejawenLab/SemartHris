<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Twig;

use KejawenLab\Application\SemartHris\Util\MonthUtil;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SemartViewExtension extends \Twig_Extension
{
    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('semarthris_month_options', [$this, 'createMonthOptions']),
            new \Twig_SimpleFunction('semarthris_year_options', [$this, 'createYearOptions']),
        ];
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return [
            new \Twig_SimpleFilter('semarthris_month_text', [$this, 'convertToMonthText']),
        ];
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function createMonthOptions(Request $request): string
    {
        $selected = $request->query->get('month', date('n'));

        $options = '';
        foreach (MonthUtil::getMonths() as $key => $month) {
            $options .= sprintf('<option value="%d" %s>%s</option>', $key, $selected == $key ? 'selected="selected"' : '', $month);
        }

        return $options;
    }

    /**
     * @param int     $limit
     * @param Request $request
     *
     * @return string
     */
    public function createYearOptions($limit = 7, Request $request): string
    {
        $yearNow = date('Y');
        $selected = $request->query->get('year', $yearNow);

        $options = '';
        for ($i = 0; $i <= $limit; ++$i) {
            $year = $yearNow - $i;
            $options .= sprintf('<option value="%d" %s>%s</option>', $year, $year == $selected ? 'selected="selected"' : '', $year);
        }

        return $options;
    }

    /**
     * @param int $month
     *
     * @return null|string
     */
    public function convertToMonthText(int $month): ? string
    {
        return MonthUtil::convertToText($month);
    }
}
