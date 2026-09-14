<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Screening\Service;

use KejawenLab\Application\SemartHris\Component\Screening\ScreeningCriterion;
use KejawenLab\Application\SemartHris\Component\Screening\ScreeningOutcome;

/**
 * Deterministic evidence scorer for phone screening results.
 * Ported from HireCall extractor logic: no fabricated confidence
 * scores, the outcome is a pure function of matched criteria.
 *
 * Input: ['experience' => bool, 'location' => bool, 'shift' => bool, 'salary' => bool]
 * Rules: 4/4 Qualified, 2-3 Maybe, 0-1 Not Fit, empty evidence Incomplete.
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ScreeningScorer
{
    /**
     * @param array $matches criterion key => matched flag
     *
     * @return array outcome, matched count, total, reason
     */
    public static function score(array $matches): array
    {
        $scored = ScreeningCriterion::getScoredKeys();

        $evidence = [];
        foreach ($scored as $key) {
            $evidence[$key] = !empty($matches[$key]);
        }

        $matched = count(array_filter($evidence));
        $total = count($scored);

        if (0 === count($matches)) {
            return [
                'outcome' => ScreeningOutcome::INCOMPLETE,
                'matched' => 0,
                'total' => $total,
                'reason' => 'Belum ada bukti percakapan yang dapat dinilai.',
            ];
        }

        if ($matched === $total) {
            $outcome = ScreeningOutcome::QUALIFIED;
            $reason = sprintf('Kandidat memenuhi seluruh %d kriteria operasional posisi.', $total);
        } elseif ($matched >= 2) {
            $outcome = ScreeningOutcome::MAYBE;
            $reason = sprintf('Kandidat memenuhi %d dari %d kriteria, perlu peninjauan recruiter.', $matched, $total);
        } else {
            $outcome = ScreeningOutcome::NOT_FIT;
            $reason = sprintf('Kandidat hanya memenuhi %d dari %d kriteria operasional posisi.', $matched, $total);
        }

        return [
            'outcome' => $outcome,
            'matched' => $matched,
            'total' => $total,
            'reason' => $reason,
        ];
    }
}
