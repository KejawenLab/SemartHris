<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Controller\Admin;

use KejawenLab\Semart\Skeleton\Component\Organization\OrganizationService;
use KejawenLab\Semart\Skeleton\Entity\Organization;
use KejawenLab\Semart\Skeleton\Pagination\Paginator;
use KejawenLab\Semart\Skeleton\Request\RequestHandler;
use KejawenLab\Semart\Skeleton\Security\Authorization\Permission;
use PHLAK\Twine\Str;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/organizations")
 *
 * @Permission(menu="ORGANIZATION")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class OrganizationController extends AdminController
{
    /**
     * @Route("/", methods={"GET"}, name="organizations_index", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function index(Request $request, Paginator $paginator, OrganizationService $organizationService)
    {
        $page = (int) $request->query->get('p', 1);
        $sort = $request->query->get('s');
        $direction = $request->query->get('d');
        $key = md5(sprintf('%s:%s:%s:%s:%s', __CLASS__, __METHOD__, $page, $sort, $direction));
        if (!$this->isCached($key)) {
            $organizations = $paginator->paginate(Organization::class, $page);
            $this->cache($key, $organizations);
        } else {
            $organizations = $this->cache($key);
        }

        if ($request->isXmlHttpRequest()) {
            $table = $this->renderView('organization/table-content.html.twig', ['organizations' => $organizations]);
            $pagination = $this->renderView('organization/pagination.html.twig', ['organizations' => $organizations]);

            $response = new JsonResponse([
                'table' => $table,
                'pagination' => $pagination,
                '_cache_id' => $key,
            ]);
        } else {
            $level = (int) $request->query->get('l', 1);

            $response = $this->render('organization/index.html.twig', [
                'title' => sprintf('Organisasi Level %s', Str::make(OrganizationService::convertLevelToText($level))->uppercaseWords()),
                'organizations' => $organizations,
                'parents' => $organizationService->getByLevel($level - 1),
                'cacheId' => $key,
            ]);
        }

        return $response;
    }

    /**
     * @Route("/{id}", methods={"GET"}, name="organizations_detail", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function find(string $id, OrganizationService $service, SerializerInterface $serializer)
    {
        $organization = $service->get($id);
        if (!$organization) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($serializer->serialize($organization, 'json', ['groups' => ['read']]));
    }

    /**
     * @Route("/save", methods={"POST"}, name="organizations_save", options={"expose"=true})
     *
     * @Permission(actions={Permission::ADD, Permission::EDIT})
     */
    public function save(Request $request, OrganizationService $service, RequestHandler $requestHandler)
    {
        $primary = $request->get('id');
        if ($primary) {
            $organization = $service->get($primary);
        } else {
            $organization = new Organization();
        }

        $requestHandler->handle($request, $organization);
        if (!$requestHandler->isValid()) {
            return new JsonResponse(['status' => 'KO', 'errors' => $requestHandler->getErrors()]);
        }

        $this->commit($organization);

        return new JsonResponse(['status' => 'OK']);
    }

    /**
     * @Route("/{id}/delete", methods={"POST"}, name="organizations_remove", options={"expose"=true})
     *
     * @Permission(actions=Permission::DELETE)
     */
    public function delete(string $id, OrganizationService $service)
    {
        if (!$organization = $service->get($id)) {
            throw new NotFoundHttpException();
        }

        $this->remove($organization);

        return new JsonResponse(['status' => 'OK']);
    }
}
