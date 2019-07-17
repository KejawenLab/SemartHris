<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\TestCase;

use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class DatabaseTestCase extends CommandTestCase
{
    const NOT_FOUND = 'NOT_FOUND';

    /** @var EntityManagerInterface */
    protected static $entityManager;

    public function setUp()
    {
        static::bootKernel();

        static::runCommand('doctrine:database:drop --force');
        static::runCommand('doctrine:database:create');
        static::runCommand('doctrine:schema:create');
        static::runCommand('doctrine:fixtures:load --append --no-interaction');

        static::$entityManager = static::$container->get('doctrine.orm.entity_manager');

        parent::setUp();
    }

    public function tearDown()
    {
        static::runCommand('doctrine:database:drop --force');

        static::$entityManager = static::$container->get('doctrine.orm.entity_manager');
        static::$entityManager->close();
        static::$entityManager = null;

        parent::tearDown();
    }
}
