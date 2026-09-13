<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Screening;

/**
 * Call statuses for a phone screening run.
 * Ported from HireCall src/types/index.ts CandidateStatus.
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ScreeningStatus
{
    const READY = 'ready';
    const QUEUED = 'queued';
    const CALLING = 'calling';
    const COMPLETED = 'completed';
    const NOT_ANSWERED = 'not_answered';
    const FAILED = 'failed';

    const READY_TEXT = 'Siap Screening';
    const QUEUED_TEXT = 'Antre';
    const CALLING_TEXT = 'Ditelepon';
    const COMPLETED_TEXT = 'Selesai';
    const NOT_ANSWERED_TEXT = 'Tidak Dijawab';
    const FAILED_TEXT = 'Gagal';
}
