<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param SerializerInterface $serializer
     * @param LoggerInterface     $logger
     */
    public function __construct(SerializerInterface $serializer, LoggerInterface $logger)
    {
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        if ($exception instanceof HttpExceptionInterface) {
            return;
        }

        if (!$event->getRequest()->isXmlHttpRequest()) {
            return;
        }

        $this->logger->error(sprintf('Message: %s => Trace: %s', $exception->getMessage(), $exception->getTraceAsString()));

        $response = new Response();
        $response->setContent($this->serializer->serialize([
            'file' => $exception->getFile(),
            'message' => $exception->getMessage(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
        ], 'json'));
        $response->setStatusCode(Response::HTTP_NOT_ACCEPTABLE);

        $event->setResponse($response);
        $event->stopPropagation();
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', -17],
        ];
    }
}
