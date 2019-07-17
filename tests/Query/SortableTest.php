<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Query;

use KejawenLab\Semart\Skeleton\Query\Sortable;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SortableTest extends TestCase
{
    public function testFields()
    {
        $this->assertCount(0, (new Sortable())->getFields());
        $this->assertCount(1, (new Sortable(['fields' => ['name']]))->getFields());
        $this->assertCount(0, (new Sortable(['fields' => 'invalid']))->getFields());
        $this->assertCount(3, (new Sortable(['value' => ['a', 'b', 'c']]))->getFields());
    }
}
