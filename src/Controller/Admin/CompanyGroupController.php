<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Controller\Admin;

use KejawenLab\Semart\Skeleton\Component\Company\CompanyGroupService;
use KejawenLab\Semart\Skeleton\Entity\CompanyGroup;
use KejawenLab\Semart\Skeleton\Pagination\Paginator;
use KejawenLab\Semart\Skeleton\Request\RequestHandler;
use KejawenLab\Semart\Skeleton\Security\Authorization\Permission;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/company-groups")
 *
 * @Permission(menu="COMPANYGROUP")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CompanyGroupController extends AdminController
{
    /**
     * @Route("/", methods={"GET"}, name="company_groups_index", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function index(Request $request, Paginator $paginator)
    {
        $page = (int) $request->query->get('p', 1);
        $sort = $request->query->get('s');
        $direction = $request->query->get('d');
        $key = md5(sprintf('%s:%s:%s:%s:%s', __CLASS__, __METHOD__, $page, $sort, $direction));
        if (!$this->isCached($key)) {
            $companygroups = $paginator->paginate(CompanyGroup::class, $page);
            $this->cache($key, $companygroups);
        } else {
            $companygroups = $this->cache($key);
        }

        if ($request->isXmlHttpRequest()) {
            $table = $this->renderView('company_group/table-content.html.twig', ['companyGroups' => $companygroups]);
            $pagination = $this->renderView('company_group/pagination.html.twig', ['companyGroups' => $companygroups]);

            $response = new JsonResponse([
                'table' => $table,
                'pagination' => $pagination,
                '_cache_id' => $key,
            ]);
        } else {
            $response = $this->render('company_group/index.html.twig', [
                'title' => 'Grup Perusahaan',
                'companyGroups' => $companygroups,
                'cacheId' => $key,
            ]);
        }

        return $response;
    }

    /**
     * @Route("/{id}", methods={"GET"}, name="company_groups_detail", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function find(string $id, CompanyGroupService $service, SerializerInterface $serializer)
    {
        $companygroup = $service->get($id);
        if (!$companygroup) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($serializer->serialize($companygroup, 'json', ['groups' => ['read']]));
    }

    /**
     * @Route("/save", methods={"POST"}, name="company_groups_save", options={"expose"=true})
     *
     * @Permission(actions={Permission::ADD, Permission::EDIT})
     */
    public function save(Request $request, CompanyGroupService $service, RequestHandler $requestHandler)
    {
        $primary = $request->get('id');
        if ($primary) {
            $companygroup = $service->get($primary);
        } else {
            $companygroup = new CompanyGroup();
        }

        $requestHandler->handle($request, $companygroup);
        if (!$requestHandler->isValid()) {
            return new JsonResponse(['status' => 'KO', 'errors' => $requestHandler->getErrors()]);
        }

        $this->commit($companygroup);

        return new JsonResponse(['status' => 'OK']);
    }

    /**
     * @Route("/{id}/delete", methods={"POST"}, name="company_groups_remove", options={"expose"=true})
     *
     * @Permission(actions=Permission::DELETE)
     */
    public function delete(string $id, CompanyGroupService $service)
    {
        if (!$companygroup = $service->get($id)) {
            throw new NotFoundHttpException();
        }

        $this->remove($companygroup);

        return new JsonResponse(['status' => 'OK']);
    }
}
