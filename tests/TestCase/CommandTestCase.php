<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\TestCase;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Input\StringInput;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CommandTestCase extends KernelTestCase
{
    /**
     * @var Application
     */
    protected static $application;

    protected static function bootKernel(array $options = [])
    {
        parent::bootKernel();

        static::$application = new Application(static::$kernel);
        static::$application->setAutoExit(false);
    }

    protected static function runCommand(string $command)
    {
        if (null === static::$application) {
            static::bootKernel();
        }

        static::$application->run(new StringInput(sprintf('%s --quiet', $command)));
    }
}
