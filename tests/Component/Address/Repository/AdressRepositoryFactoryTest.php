<?php

namespace Tests\KejawenLab\Application\SemartHris\Component\Address\Repository;

use KejawenLab\Application\SemartHris\Component\Address\Repository\AddressRepositoryFactory;
use KejawenLab\Application\SemartHris\Component\Address\Repository\AddressRepositoryInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
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

        $this->addressRepositoryFactory = new AddressRepositoryFactory([$firstRepository, $secondRepostiroy, new AddressRepositoryStub()]);
    }

    public function testGetRepositoryForClass()
    {
        $this->assertInstanceOf(AddressRepositoryInterface::class, $this->addressRepositoryFactory->getRepositoryFor(AddressRepositoryStub::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNonExistRepository()
    {
        $this->addressRepositoryFactory->getRepositoryFor(InvalidAddressRepositoryStub::class);
    }

    /**
     * @expectedException \TypeError
     */
    public function testInvalidRepositoryTest()
    {
        (new AddressRepositoryFactory([new InvalidAddressRepositoryStub()]));
    }
}
