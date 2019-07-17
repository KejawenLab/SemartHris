<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\EventSubscriber;

use KejawenLab\Semart\Skeleton\Entity\User;
use KejawenLab\Semart\Skeleton\EventSubscriber\EncodePasswordSubscriber;
use KejawenLab\Semart\Skeleton\Request\RequestEvent;
use KejawenLab\Semart\Skeleton\Security\Service\PasswordEncoderService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class EncodePasswordSubscriberTest extends KernelTestCase
{
    public function testNormalize()
    {
        static::bootKernel();

        $request = Request::createFromGlobals();
        $request->request->set('a', 'true');
        $request->request->set('b', 'false');

        $user = new User();
        $event = new RequestEvent($request, $user);

        $passwordEncoderService = $this->getMockBuilder(PasswordEncoderService::class)->disableOriginalConstructor()->getMock();
        $passwordEncoderService
            ->method('encode')
            ->withAnyParameters()
            ->willReturn(null)
        ;

        $passwordEncoder = new EncodePasswordSubscriber($passwordEncoderService);

        $this->assertNull($passwordEncoder->setPassword($event));
    }

    public function testGetSubscribedEvents()
    {
        $this->assertCount(1, EncodePasswordSubscriber::getSubscribedEvents());
    }
}
