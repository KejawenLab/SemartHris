<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Security\Service;

use KejawenLab\Semart\Skeleton\Entity\User;
use KejawenLab\Semart\Skeleton\Repository\UserRepository;
use KejawenLab\Semart\Skeleton\Security\Service\UserService;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class UserServiceTest extends TestCase
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var UserService
     */
    private $userProviderService;

    public function setUp()
    {
        $this->user = new User();
        $this->user->setUsername('test');

        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();
        $userRepositoryMock
            ->method('findOneBy')
            ->with(
                $this->logicalOr(
                    ['username' => $this->user->getUsername()],
                    ['username' => 'foo']
                )
            )
            ->willReturnCallback(
                function (array $parameter) {
                    if ('foo' === $parameter['username']) {
                        return null;
                    }

                    return $this->user;
                }
            )
        ;
        $userRepositoryMock
            ->method('find')
            ->withAnyParameters()
            ->willReturn(null)
        ;

        $this->userProviderService = new UserService($userRepositoryMock);
    }

    public function testProvider()
    {
        $this->assertEquals($this->user->getUsername(), $this->userProviderService->loadUserByUsername($this->user->getUsername())->getUsername());
        $this->assertEquals($this->user->getUsername(), $this->userProviderService->refreshUser($this->user)->getUsername());
        $this->assertTrue($this->userProviderService->supportsClass(User::class));
        $this->assertNull($this->userProviderService->get('foo'));
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testUserNotFound()
    {
        $this->userProviderService->loadUserByUsername('foo');
    }
}
