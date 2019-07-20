<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Controller\Admin;

use KejawenLab\Semart\Skeleton\Component\Address\ProvinceService;
use KejawenLab\Semart\Skeleton\Component\Address\SubDistrictService;
use KejawenLab\Semart\Skeleton\Entity\SubDistrict;
use KejawenLab\Semart\Skeleton\Pagination\Paginator;
use KejawenLab\Semart\Skeleton\Request\RequestHandler;
use KejawenLab\Semart\Skeleton\Security\Authorization\Permission;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/sub-districts")
 *
 * @Permission(menu="SUBDISTRICT")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SubDistrictController extends AdminController
{
    /**
     * @Route("/", methods={"GET"}, name="sub_districts_index", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function index(Request $request, Paginator $paginator, ProvinceService $provinceService)
    {
        $page = (int) $request->query->get('p', 1);
        $sort = $request->query->get('s');
        $direction = $request->query->get('d');
        $key = md5(sprintf('%s:%s:%s:%s:%s', __CLASS__, __METHOD__, $page, $sort, $direction));
        if (!$this->isCached($key)) {
            $subdistricts = $paginator->paginate(SubDistrict::class, $page);
            $this->cache($key, $subdistricts);
        } else {
            $subdistricts = $this->cache($key);
        }

        if ($request->isXmlHttpRequest()) {
            $table = $this->renderView('sub_district/table-content.html.twig', ['subDistricts' => $subdistricts]);
            $pagination = $this->renderView('sub_district/pagination.html.twig', ['subDistricts' => $subdistricts]);

            $response = new JsonResponse([
                'table' => $table,
                'pagination' => $pagination,
                '_cache_id' => $key,
            ]);
        } else {
            $response = $this->render('sub_district/index.html.twig', [
                'title' => 'Kecamatan',
                'subDistricts' => $subdistricts,
                'provinces' => $provinceService->getAll(),
                'cacheId' => $key,
            ]);
        }

        return $response;
    }

    /**
     * @Route("/{id}", methods={"GET"}, name="sub_districts_detail", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function find(string $id, SubDistrictService $service, SerializerInterface $serializer)
    {
        $subdistrict = $service->get($id);
        if (!$subdistrict) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($serializer->serialize($subdistrict, 'json', ['groups' => ['read']]));
    }

    /**
     * @Route("/save", methods={"POST"}, name="sub_districts_save", options={"expose"=true})
     *
     * @Permission(actions={Permission::ADD, Permission::EDIT})
     */
    public function save(Request $request, SubDistrictService $service, RequestHandler $requestHandler)
    {
        $primary = $request->get('id');
        if ($primary) {
            $subdistrict = $service->get($primary);
        } else {
            $subdistrict = new SubDistrict();
        }

        $requestHandler->handle($request, $subdistrict);
        if (!$requestHandler->isValid()) {
            return new JsonResponse(['status' => 'KO', 'errors' => $requestHandler->getErrors()]);
        }

        $this->commit($subdistrict);

        return new JsonResponse(['status' => 'OK']);
    }

    /**
     * @Route("/{id}/delete", methods={"POST"}, name="sub_districts_remove", options={"expose"=true})
     *
     * @Permission(actions=Permission::DELETE)
     */
    public function delete(string $id, SubDistrictService $service)
    {
        if (!$subdistrict = $service->get($id)) {
            throw new NotFoundHttpException();
        }

        $this->remove($subdistrict);

        return new JsonResponse(['status' => 'OK']);
    }
}
