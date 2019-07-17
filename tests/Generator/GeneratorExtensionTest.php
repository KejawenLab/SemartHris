<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Generator;

use KejawenLab\Semart\Skeleton\Generator\GeneratorExtension;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class GeneratorExtensionTest extends TestCase
{
    public function testGetFilters()
    {
        $this->assertCount(5, (new GeneratorExtension())->getFilters());
    }

    public function testPluralize()
    {
        $this->assertEquals('employees', (new GeneratorExtension())->pluralize('employee'));
        $this->assertEquals('companies', (new GeneratorExtension())->pluralize('company'));
    }

    public function testHumanize()
    {
        $this->assertEquals('Surya', (new GeneratorExtension())->humanize('surya'));
        $this->assertEquals('Surya iksanudin', (new GeneratorExtension())->humanize('surya iksanudin'));
    }

    public function testUnderscore()
    {
        $this->assertEquals('surya_iksanudin', (new GeneratorExtension())->underscore('suryaIksanudin'));
    }

    public function testDash()
    {
        $this->assertEquals('surya-iksanudin', (new GeneratorExtension())->dash('suryaIksanudin'));
    }

    public function testCamelcase()
    {
        $this->assertEquals('suryaIksanudin', (new GeneratorExtension())->camelcase('surya iksanudin'));
    }
}
