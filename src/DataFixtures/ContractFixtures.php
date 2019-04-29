<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\DataFixtures;

use KejawenLab\Application\SemartHris\Entity\Contract;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
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
