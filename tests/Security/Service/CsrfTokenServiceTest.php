<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Security\Service;

use KejawenLab\Semart\Skeleton\Request\RequestHandler;
use KejawenLab\Semart\Skeleton\Security\Service\CsrfTokenService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CsrfTokenServiceTest extends TestCase
{
    private $requestMock;

    public function setUp()
    {
        $request = Request::createFromGlobals();
        $request->request->set('_csrf_token', 'test');
        $request->setMethod('POST');
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');

        $requestMock = $this->getMockBuilder(RequestStack::class)->disableOriginalConstructor()->getMock();
        $requestMock
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request)
        ;

        $this->requestMock = $requestMock;
    }

    public function testValidate()
    {
        $csrfManagerMock = $this->getMockBuilder(CsrfTokenManagerInterface::class)->disableOriginalConstructor()->getMock();
        $csrfManagerMock
            ->expects($this->at(0))
            ->method('isTokenValid')
            ->withAnyParameters()
            ->willReturn(false)
        ;
        $csrfManagerMock
            ->expects($this->at(1))
            ->method('isTokenValid')
            ->withAnyParameters()
            ->willReturn(true)
        ;

        $service = new CsrfTokenService($csrfManagerMock, $this->requestMock);

        $this->assertFalse($service->validate());
        $this->assertTrue($service->validate());
    }

    public function testApply()
    {
        $csrfManagerMock = $this->getMockBuilder(CsrfTokenManagerInterface::class)->disableOriginalConstructor()->getMock();
        $csrfManagerMock
            ->expects($this->once())
            ->method('refreshToken')
            ->withAnyParameters()
            ->willReturn(new CsrfToken(RequestHandler::REQUEST_TOKEN_NAME, 'test'))
        ;

        $service = new CsrfTokenService($csrfManagerMock, $this->requestMock);

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $this->assertEquals(JsonResponse::class, \get_class($service->apply($response)));

        $response = new Response();
        $responseService = $service->apply($response);
        $this->assertNotEquals(JsonResponse::class, \get_class($responseService));
        $this->assertEquals(Response::class, \get_class($service->apply($response)));
    }
}
