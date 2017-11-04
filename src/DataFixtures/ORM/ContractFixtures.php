<?php

namespace KejawenLab\Application\SemartHris\DataFixtures\ORM;

use KejawenLab\Application\SemartHris\Entity\Contract;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.id>
 */
class ContractFixtures extends Fixture
{
    /**
     * @return string
     */
    protected function getFixtureFilePath(): string
    {
        return 'contract.yaml';
    }

    /**
     * @return mixed
     */
    protected function createNew()
    {
        return new Contract();
    }

    /**
     * @return string
     */
    protected function getReferenceKey(): string
    {
        return 'contract';
    }
}
