<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Controller\Admin;

use KejawenLab\Semart\Skeleton\Entity\Group;
use KejawenLab\Semart\Skeleton\Entity\Role;
use KejawenLab\Semart\Skeleton\Request\RequestHandler;
use KejawenLab\Semart\Skeleton\Security\Authorization\Permission;
use KejawenLab\Semart\Skeleton\Security\Service\GroupService;
use KejawenLab\Semart\Skeleton\Security\Service\RoleService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/roles")
 *
 * @Permission(menu="GROUP")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RoleController extends AdminController
{
    /**
     * @Route("/user_roles", methods={"GET"}, name="user_roles", options={"expose"=true})
     *
     * @Permission(actions={Permission::ADD, Permission::EDIT})
     */
    public function userRoles(Request $request, GroupService $groupService, RoleService $roleService)
    {
        $group = $groupService->get($request->query->get('groupId'));
        if (!$group instanceof Group) {
            throw new NotFoundHttpException();
        }

        $roles = $roleService->getRolesByGroup($group, $request->query->get('q', ''));

        $table = $this->renderView('role/table-content.html.twig', ['roles' => $roles]);

        return new JsonResponse([
            'table' => $table,
        ]);
    }

    /**
     * @Route("/save", methods={"POST"}, name="roles_save", options={"expose"=true})
     *
     * @Permission(actions={Permission::ADD, Permission::EDIT})
     */
    public function save(Request $request, RoleService $service, RequestHandler $requestHandler)
    {
        $primary = $request->get('id');
        if ($primary) {
            $role = $service->get($primary);
        } else {
            $role = new Role();
        }

        if (!$role) {
            throw new NotFoundHttpException();
        }

        $requestHandler->handle($request, $role);
        if (!$requestHandler->isValid()) {
            return new JsonResponse(['status' => 'KO', 'errors' => $requestHandler->getErrors()]);
        }

        $this->commit($role);

        return new JsonResponse(['status' => 'OK']);
    }
}
