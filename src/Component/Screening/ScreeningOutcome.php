<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Screening;

/**
 * Recruiter-facing screening outcomes.
 * Ported from HireCall src/types/index.ts ScreeningOutcome.
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ScreeningOutcome
{
    const QUALIFIED = 'Qualified';
    const MAYBE = 'Maybe';
    const NOT_FIT = 'Not Fit';
    const INCOMPLETE = 'Incomplete';

    const QUALIFIED_TEXT = 'Lolos';
    const MAYBE_TEXT = 'Pertimbangkan';
    const NOT_FIT_TEXT = 'Tidak Lolos';
    const INCOMPLETE_TEXT = 'Belum Lengkap';
}
