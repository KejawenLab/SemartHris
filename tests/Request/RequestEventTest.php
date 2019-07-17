<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Request;

use KejawenLab\Semart\Skeleton\Request\RequestEvent;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RequestEventTest extends TestCase
{
    public function testObject()
    {
        $filterRequest = new RequestEvent(Request::createFromGlobals(), new \stdClass());

        $this->assertInstanceOf(Request::class, $filterRequest->getRequest());
        $this->assertInstanceOf(\stdClass::class, $filterRequest->getObject());
    }
}
