<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Controller\Admin;

use KejawenLab\Semart\Skeleton\Component\Address\ProvinceService;
use KejawenLab\Semart\Skeleton\Component\Company\CompanyService;
use KejawenLab\Semart\Skeleton\Entity\Company;
use KejawenLab\Semart\Skeleton\Pagination\Paginator;
use KejawenLab\Semart\Skeleton\Request\RequestHandler;
use KejawenLab\Semart\Skeleton\Security\Authorization\Permission;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/companies")
 *
 * @Permission(menu="COMPANY")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CompanyController extends AdminController
{
    /**
     * @Route("/", methods={"GET"}, name="companies_index", options={"expose"=true})
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
            $companies = $paginator->paginate(Company::class, $page);
            $this->cache($key, $companies);
        } else {
            $companies = $this->cache($key);
        }

        if ($request->isXmlHttpRequest()) {
            $table = $this->renderView('company/table-content.html.twig', ['companies' => $companies]);
            $pagination = $this->renderView('company/pagination.html.twig', ['companies' => $companies]);

            $response = new JsonResponse([
                'table' => $table,
                'pagination' => $pagination,
                '_cache_id' => $key,
            ]);
        } else {
            $response = $this->render('company/index.html.twig', [
                'title' => 'Perusahaan',
                'companies' => $companies,
                'cacheId' => $key,
            ]);
        }

        return $response;
    }

    /**
     * @Route("/create", methods={"GET"}, name="companies_create", options={"expose"=true})
     *
     * @Permission(actions=Permission::ADD)
     */
    public function create(ProvinceService $provinceService)
    {
        return $this->render('company/form.html.twig', [
            'title' => 'Perusahaan',
            'provinces' => $provinceService->getAll(),
            'cacheId' => 'invalid',//Just dummy key
        ]);
    }

    /**
     * @Route("/{id}", methods={"GET"}, name="companies_detail", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function find(string $id, CompanyService $service, ProvinceService $provinceService)
    {
        $company = $service->get($id);
        if (!$company) {
            throw new NotFoundHttpException();
        }

        return $this->render('company/form.html.twig', [
            'title' => 'Perusahaan',
            'provinces' => $provinceService->getAll(),
            'company' => $company,
            'cacheId' => 'invalid',//Just dummy key
        ]);
    }

    /**
     * @Route("/save", methods={"POST"}, name="companies_save", options={"expose"=true})
     *
     * @Permission(actions={Permission::ADD, Permission::EDIT})
     */
    public function save(Request $request, CompanyService $service, RequestHandler $requestHandler)
    {
        $primary = $request->get('id');
        if ($primary) {
            $company = $service->get($primary);
        } else {
            $company = new Company();
        }

        $requestHandler->handle($request, $company);
        if (!$requestHandler->isValid()) {
            return new JsonResponse(['status' => 'KO', 'errors' => $requestHandler->getErrors()]);
        }

        $this->commit($company);

        return new JsonResponse(['status' => 'OK']);
    }

    /**
     * @Route("/{id}/delete", methods={"POST"}, name="companies_remove", options={"expose"=true})
     *
     * @Permission(actions=Permission::DELETE)
     */
    public function delete(string $id, CompanyService $service)
    {
        if (!$company = $service->get($id)) {
            throw new NotFoundHttpException();
        }

        $this->remove($company);

        return new JsonResponse(['status' => 'OK']);
    }
}
