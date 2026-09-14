<?php

namespace Tests\KejawenLab\Application\SemartHris\Component\Screening\Service;

use KejawenLab\Application\SemartHris\Component\Screening\ScreeningStatus;
use KejawenLab\Application\SemartHris\Component\Screening\Service\ValidateScreeningStatus;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ValidateScreeningStatusTest extends TestCase
{
    public function testValidStatuses()
    {
        $this->assertTrue(ValidateScreeningStatus::isValidType(ScreeningStatus::READY));
        $this->assertTrue(ValidateScreeningStatus::isValidType(ScreeningStatus::CALLING));
        $this->assertTrue(ValidateScreeningStatus::isValidType(ScreeningStatus::COMPLETED));
    }

    public function testInvalidStatus()
    {
        $this->assertFalse(ValidateScreeningStatus::isValidType('dialing'));
    }

    public function testConvertToText()
    {
        $this->assertSame(ScreeningStatus::READY_TEXT, ValidateScreeningStatus::convertToText(ScreeningStatus::READY));
        $this->assertSame('', ValidateScreeningStatus::convertToText('dialing'));
    }
}
