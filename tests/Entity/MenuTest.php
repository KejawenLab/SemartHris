<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Entity;

use KejawenLab\Semart\Skeleton\Entity\Menu;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class MenuTest extends TestCase
{
    public function testObject()
    {
        $this->assertEquals(Menu::class, \get_class(new Menu()));
    }
}
