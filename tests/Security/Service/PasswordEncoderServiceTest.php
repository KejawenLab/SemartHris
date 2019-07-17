<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Security\Service;

use KejawenLab\Semart\Skeleton\Entity\User;
use KejawenLab\Semart\Skeleton\Security\Service\PasswordEncoderService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class PasswordEncoderServiceTest extends TestCase
{
    public function testEncode()
    {
        $user = new User();
        $user->setPlainPassword('test');

        $passwordEncoderMock = $this->getMockBuilder(UserPasswordEncoderInterface::class)->disableOriginalConstructor()->getMock();
        $passwordEncoderMock
            ->expects($this->once())
            ->method('encodePassword')
            ->with($user, 'test')
            ->willReturn('asdf')
        ;

        $service = new PasswordEncoderService($passwordEncoderMock);
        $service->encode($user);

        $this->assertEquals('asdf', $user->getPassword());
    }
}
