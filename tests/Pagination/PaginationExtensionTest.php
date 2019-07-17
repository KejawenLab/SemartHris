<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Pagination;

use KejawenLab\Semart\Skeleton\Entity\Setting;
use KejawenLab\Semart\Skeleton\Pagination\PaginationExtension;
use KejawenLab\Semart\Skeleton\Pagination\Paginator;
use KejawenLab\Semart\Skeleton\Repository\SettingRepository;
use KejawenLab\Semart\Skeleton\Setting\SettingService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class PaginationExtensionTest extends TestCase
{
    const NOT_EXIST = 'NOT_EXIST';

    public function testStartPageNumber()
    {
        $request = Request::createFromGlobals();
        $request->query->set('page', 1);

        $requestStackMock = $this->getMockBuilder(RequestStack::class)->disableOriginalConstructor()->getMock();
        $requestStackMock
            ->expects($this->atLeastOnce())
            ->method('getCurrentRequest')
            ->willReturn($request)
        ;

        $setting = new Setting();
        $setting->setParameter('PER_PAGE');
        $setting->setValue(Paginator::PER_PAGE);

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
                    if (static::NOT_EXIST === $parameter['parameter']) {
                        return null;
                    }

                    return $setting;
                }
            )
        ;

        $service = new SettingService($repositoryMock);

        $this->assertEquals(1, (new PaginationExtension($requestStackMock, $service))->startPageNumber());

        $request->query->set('page', 2);

        $this->assertEquals(Paginator::PER_PAGE + 1, (new PaginationExtension($requestStackMock, $service))->startPageNumber());
    }

    public function testGetFunctions()
    {
        $request = Request::createFromGlobals();
        $request->query->set('page', 1);

        $requestStackMock = $this->getMockBuilder(RequestStack::class)->disableOriginalConstructor()->getMock();
        $requestStackMock
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request)
        ;

        $setting = new Setting();
        $setting->setParameter('PER_PAGE');
        $setting->setValue(Paginator::PER_PAGE);

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
                    if (static::NOT_EXIST === $parameter['parameter']) {
                        return null;
                    }

                    return $setting;
                }
            )
        ;

        $this->assertCount(1, (new PaginationExtension($requestStackMock, new SettingService($repositoryMock)))->getFunctions());
    }
}
