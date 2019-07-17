<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Security\Service;

use KejawenLab\Semart\Skeleton\Request\RequestHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CsrfTokenService
{
    private $crsfTokenManager;

    private $request;

    public function __construct(CsrfTokenManagerInterface $csrfTokenManager, RequestStack $requestStack)
    {
        $this->crsfTokenManager = $csrfTokenManager;
        $this->request = $requestStack->getCurrentRequest();
    }

    public function validate(): bool
    {
        if ($this->request->isMethod('POST') && $this->request->isXmlHttpRequest()) {
            return $this->crsfTokenManager->isTokenValid(new CsrfToken(RequestHandler::REQUEST_TOKEN_NAME, $this->request->request->get('_csrf_token')));
        }

        return true;
    }

    public function apply(Response $response): Response
    {
        if (
            Response::HTTP_OK === $response->getStatusCode() &&
            $this->request->isMethod('POST') &&
            $this->request->isXmlHttpRequest() &&
            'application/json' === $response->headers->get('Content-Type')
        ) {
            $content = \json_decode($response->getContent(), true);
            $content['_csrf_token'] = $this->crsfTokenManager->refreshToken(RequestHandler::REQUEST_TOKEN_NAME)->getValue();

            return new JsonResponse($content);
        }

        return $response;
    }
}
