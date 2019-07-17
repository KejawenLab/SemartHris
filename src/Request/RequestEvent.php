<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Request;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RequestEvent extends Event
{
    private $request;

    private $object;

    public function __construct(Request $request, object $object)
    {
        $this->request = $request;
        $this->object = $object;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return object
     */
    public function getObject(): object
    {
        return $this->object;
    }
}
