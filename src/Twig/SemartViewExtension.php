<?php

namespace KejawenLab\Application\SemartHris\Twig;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class SemartViewExtension extends \Twig_Extension
{
    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return array(
            new \Twig_SimpleFunction('semarthris_month_options', array($this, 'createMonthOptions')),
            new \Twig_SimpleFunction('semarthris_year_options', array($this, 'createYearOptions')),
        );
    }

    /**
     * @return string
     */
    public function createMonthOptions(): string
    {
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $options = '';
        foreach ($months as $key => $month) {
            $options .= sprintf('<option value="%d" %s>%s</option>', $key, date('n') == $key ? 'selected="selected"' : '', $month);
        }

        return $options;
    }

    /**
     * @param int $limit
     *
     * @return string
     */
    public function createYearOptions($limit = 7): string
    {
        $yearNow = date('Y');

        $options = '';
        for ($i = 0; $i <= $limit; ++$i) {
            $year = $yearNow - $i;
            $options .= sprintf('<option value="%d" %s>%s</option>', $year, $year == $yearNow ? 'selected="selected"' : '', $year);
        }

        return $options;
    }
}
