<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Setting;

use KejawenLab\Semart\Skeleton\Entity\Setting;
use KejawenLab\Semart\Skeleton\Repository\SettingRepository;
use KejawenLab\Semart\Skeleton\Setting\SettingExtension;
use KejawenLab\Semart\Skeleton\Setting\SettingService;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SettingExtensionTest extends TestCase
{
    const NOT_EXIST = 'NOT_EXIST';

    /**
     * @var SettingExtension
     */
    private $settingExtension;

    private $setting;

    public function setUp()
    {
        $setting = new Setting();
        $setting->setParameter('PER_PAGE');
        $setting->setValue(17);

        $repositoryMock = $this->getMockBuilder(SettingRepository::class)->disableOriginalConstructor()->getMock();
        $repositoryMock
            ->method('findOneBy')
            ->with(
                $this->logicalOr(
                    ['parameter' => $setting->getParameter()],
                    ['parameter' => static::NOT_EXIST]
                )
            )
            ->willReturnCallback(
                function (array $parameter) use ($setting) {
                    if (static::NOT_EXIST === $parameter['parameter']->__toString()) {
                        return null;
                    }

                    return $setting;
                }
            )
        ;

        $this->setting = $setting;
        $this->settingExtension = new SettingExtension(new SettingService($repositoryMock));
    }

    public function testGetSettingValue()
    {
        $this->assertEquals($this->setting->getValue(), $this->settingExtension->getSettingValue($this->setting->getParameter()));
        $this->assertNull($this->settingExtension->getSettingValue(static::NOT_EXIST));
    }

    public function testGetFunctions()
    {
        $this->assertCount(1, $this->settingExtension->getFunctions());
    }
}
