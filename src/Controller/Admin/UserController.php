<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Controller\Admin;

use KejawenLab\Semart\Skeleton\Entity\Menu;
use KejawenLab\Semart\Skeleton\Entity\User;
use KejawenLab\Semart\Skeleton\Pagination\Paginator;
use KejawenLab\Semart\Skeleton\Request\RequestHandler;
use KejawenLab\Semart\Skeleton\Security\Authorization\Permission;
use KejawenLab\Semart\Skeleton\Security\Service\GroupService;
use KejawenLab\Semart\Skeleton\Security\Service\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/users")
 *
 * @Permission(menu="USER")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class UserController extends AdminController
{
    /**
     * @Route("/", methods={"GET"}, name="users_index", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function index(Request $request, Paginator $paginator, GroupService $groupService)
    {
        $page = (int) $request->query->get('p', 1);
        $sort = $request->query->get('s');
        $direction = $request->query->get('d');
        $key = md5(sprintf('%s:%s:%s:%s:%s', __CLASS__, __METHOD__, $page, $sort, $direction));
        if (!$this->isCached($key)) {
            $users = $paginator->paginate(User::class, $page);
            $this->cache($key, $users);
        } else {
            $users = $this->cache($key);
        }

        if ($request->isXmlHttpRequest()) {
            $table = $this->renderView('user/table-content.html.twig', ['users' => $users]);
            $pagination = $this->renderView('user/pagination.html.twig', ['users' => $users]);

            $response = new JsonResponse([
                'table' => $table,
                'pagination' => $pagination,
                '_cache_id' => $key,
            ]);
        } else {
            $response = $this->render('user/index.html.twig', [
                'title' => 'Pengguna',
                'users' => $users,
                'groups' => $groupService->getActiveGroups(),
                'cacheId' => $key,
            ]);
        }

        return $response;
    }

    /**
     * @Route("/{id}", methods={"GET"}, name="users_detail", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function find(string $id, UserService $service, SerializerInterface $serializer)
    {
        $user = $service->get($id);
        if (!$user) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($serializer->serialize($user, 'json', ['groups' => ['read']]));
    }

    /**
     * @Route("/save", methods={"POST"}, name="users_save", options={"expose"=true})
     *
     * @Permission(actions={Permission::ADD, Permission::EDIT})
     */
    public function save(Request $request, UserService $service, RequestHandler $requestHandler)
    {
        $primary = $request->get('id');
        if ($primary) {
            $user = $service->get($primary);
        } else {
            $user = new User();
        }

        if (!$user) {
            throw new NotFoundHttpException();
        }

        $requestHandler->handle($request, $user);
        if (!$requestHandler->isValid()) {
            return new JsonResponse(['status' => 'KO', 'errors' => $requestHandler->getErrors()]);
        }

        $this->commit($user);

        return new JsonResponse(['status' => 'OK']);
    }

    /**
     * @Route("/{id}/delete", methods={"POST"}, name="users_remove", options={"expose"=true})
     *
     * @Permission(actions=Permission::DELETE)
     */
    public function delete(string $id, UserService $service)
    {
        if (!$user = $service->get($id)) {
            throw new NotFoundHttpException();
        }

        $this->remove($user);

        return new JsonResponse(['status' => 'OK']);
    }
}
