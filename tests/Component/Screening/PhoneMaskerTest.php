<?php

namespace Tests\KejawenLab\Application\SemartHris\Component\Screening;

use KejawenLab\Application\SemartHris\Component\Screening\PhoneMasker;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class PhoneMaskerTest extends TestCase
{
    public function testMaskHidesMiddleDigits()
    {
        $this->assertSame('+628 .... 7890', PhoneMasker::mask('+6281234567890'));
    }

    public function testShortNumbersStayUntouched()
    {
        $this->assertSame('123', PhoneMasker::mask('123'));
        $this->assertNull(PhoneMasker::mask(null));
        $this->assertSame('', PhoneMasker::mask(''));
    }
}
