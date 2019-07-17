<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Controller\Admin;

use KejawenLab\Semart\Skeleton\Entity\User;
use KejawenLab\Semart\Skeleton\Request\RequestHandler;
use KejawenLab\Semart\Skeleton\Security\Service\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ProfileController extends AdminController
{
    /**
     * @Route("/me", methods={"GET"}, name="profile_index", options={"expose"=true})
     */
    public function index()
    {
        return $this->render('user/profile.html.twig');
    }

    /**
     * @Route("/me/update", methods={"POST"}, name="profile_update", options={"expose"=true})
     */
    public function save(Request $request, UserService $service, RequestHandler $requestHandler)
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user->getId() !== $request->get('id')) {
            throw new AccessDeniedException();
        }

        $user = $service->get($user->getId());
        if (!$user) {
            throw new NotFoundHttpException();
        }
        $requestHandler->handle($request, $user);
        $this->commit($user);

        return new JsonResponse(['status' => 'OK']);
    }
}
