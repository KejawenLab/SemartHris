<?php

namespace Tests\KejawenLab\Application\SemartHris\Component\Screening\Service;

use KejawenLab\Application\SemartHris\Component\Screening\ScreeningOutcome;
use KejawenLab\Application\SemartHris\Component\Screening\Service\ScreeningScorer;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ScreeningScorerTest extends TestCase
{
    public function testQualifiedWhenAllCriteriaMatch()
    {
        $result = ScreeningScorer::score([
            'experience' => true,
            'location' => true,
            'shift' => true,
            'salary' => true,
        ]);

        $this->assertSame(ScreeningOutcome::QUALIFIED, $result['outcome']);
        $this->assertSame(4, $result['matched']);
        $this->assertSame(4, $result['total']);
    }

    public function testMaybeWhenPartialMatch()
    {
        $result = ScreeningScorer::score([
            'experience' => true,
            'location' => true,
            'shift' => false,
            'salary' => false,
        ]);

        $this->assertSame(ScreeningOutcome::MAYBE, $result['outcome']);
        $this->assertSame(2, $result['matched']);
    }

    public function testNotFitWhenMostlyMismatch()
    {
        $result = ScreeningScorer::score([
            'experience' => false,
            'location' => false,
            'shift' => false,
            'salary' => true,
        ]);

        $this->assertSame(ScreeningOutcome::NOT_FIT, $result['outcome']);
        $this->assertSame(1, $result['matched']);
    }

    public function testIncompleteWhenNoEvidence()
    {
        $result = ScreeningScorer::score([]);

        $this->assertSame(ScreeningOutcome::INCOMPLETE, $result['outcome']);
        $this->assertSame(0, $result['matched']);
    }
}
