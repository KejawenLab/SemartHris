<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Screening;

/**
 * Human-in-the-loop decisions. AI recommends, recruiter decides.
 * Ported from HireCall ScorecardCard recruiter actions.
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RecruiterDecision
{
    const INTERVIEW = 'interview';
    const BACKUP = 'backup';
    const PASS = 'pass';

    const INTERVIEW_TEXT = 'Lanjut Interview';
    const BACKUP_TEXT = 'Cadangan';
    const PASS_TEXT = 'Tolak';

    /**
     * @return array
     */
    public static function getDecisions(): array
    {
        return [
            self::INTERVIEW,
            self::BACKUP,
            self::PASS,
        ];
    }
}
