<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Query;

use KejawenLab\Semart\Skeleton\Query\Searchable;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SearchableTest extends TestCase
{
    public function testFields()
    {
        $this->assertCount(0, (new Searchable())->getFields());
        $this->assertCount(1, (new Searchable(['fields' => ['name']]))->getFields());
        $this->assertCount(0, (new Searchable(['fields' => 'invalid']))->getFields());
        $this->assertCount(3, (new Searchable(['value' => ['a', 'b', 'c']]))->getFields());
    }
}
