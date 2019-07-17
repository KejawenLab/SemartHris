<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests;

use KejawenLab\Semart\Skeleton\Kernel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class KernelTest extends TestCase
{
    public function testInstanceOf()
    {
        $kernel = new Kernel('test', true);

        $this->assertInstanceOf(KernelInterface::class, $kernel);
        $this->assertInstanceOf(CompilerPassInterface::class, $kernel);
    }
}
