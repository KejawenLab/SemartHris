<?php

namespace Tests\KejawenLab\Application\SemartHris\Component\Address\Repository;

use KejawenLab\Application\SemartHris\Component\Address\Repository\AddressRepositoryFactory;
use KejawenLab\Application\SemartHris\Component\Address\Repository\AddressRepositoryInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class AdressRepositoryFactoryTest extends TestCase
{
    /**
     * @var AddressRepositoryFactory
     */
    private $addressRepositoryFactory;

    public function setUp()
    {
        $firstRepository = $this->getMockBuilder(AddressRepositoryInterface::class)->getMock();
        $secondRepostiroy = $this->getMockBuilder(AddressRepositoryInterface::class)->getMock();

        $this->addressRepositoryFactory = new AddressRepositoryFactory([$firstRepository, $secondRepostiroy, new ValidAddressRepositoryStub()]);
    }

    public function testGetRepositoryForClass()
    {
        $this->assertInstanceOf(AddressRepositoryInterface::class, $this->addressRepositoryFactory->getRepositoryFor(ValidAddressRepositoryStub::class));
    }

    public function testNonExistRepository()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->addressRepositoryFactory->getRepositoryFor(InvalidAddressRepositoryStub::class);
    }

    public function testInvalidRepositoryTest()
    {
        $this->expectException(\TypeError::class);

        (new AddressRepositoryFactory([new InvalidAddressRepositoryStub()]));
    }
}
