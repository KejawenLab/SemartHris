<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Cache;

use KejawenLab\Semart\Skeleton\Cache\CacheHandler;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CacheHandlerTest extends KernelTestCase
{
    public function testCache()
    {
        $adapter = new ArrayAdapter();
        $cacheHandler = new CacheHandler($adapter);

        $key = 'foo';
        $content = 'bar';

        $this->assertFalse($cacheHandler->isCached($key));
        $this->assertNull($cacheHandler->getItem($key));
        $cacheHandler->cache($key, $content);
        $this->assertTrue($cacheHandler->isCached($key));
        $this->assertEquals($content, $cacheHandler->getItem($key));
    }
}
