<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Controller\Admin;

use KejawenLab\Semart\Skeleton\Component\Address\DistrictService;
use KejawenLab\Semart\Skeleton\Component\Address\ProvinceService;
use KejawenLab\Semart\Skeleton\Entity\District;
use KejawenLab\Semart\Skeleton\Pagination\Paginator;
use KejawenLab\Semart\Skeleton\Request\RequestHandler;
use KejawenLab\Semart\Skeleton\Security\Authorization\Permission;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/districts")
 *
 * @Permission(menu="DISTRICT")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class DistrictController extends AdminController
{
    /**
     * @Route("/", methods={"GET"}, name="districts_index", options={"expose"=true})
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
            $districts = $paginator->paginate(District::class, $page);
            $this->cache($key, $districts);
        } else {
            $districts = $this->cache($key);
        }

        if ($request->isXmlHttpRequest()) {
            $table = $this->renderView('district/table-content.html.twig', ['districts' => $districts]);
            $pagination = $this->renderView('district/pagination.html.twig', ['districts' => $districts]);

            $response = new JsonResponse([
                'table' => $table,
                'pagination' => $pagination,
                '_cache_id' => $key,
            ]);
        } else {
            $response = $this->render('district/index.html.twig', [
                'title' => 'Kabupaten',
                'districts' => $districts,
                'provinces' => $provinceService->getAll(),
                'cacheId' => $key,
            ]);
        }

        return $response;
    }

    /**
     * @Route("/{id}", methods={"GET"}, name="districts_detail", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function find(string $id, DistrictService $service, SerializerInterface $serializer)
    {
        $district = $service->get($id);
        if (!$district) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($serializer->serialize($district, 'json', ['groups' => ['read']]));
    }

    /**
     * @Route("/save", methods={"POST"}, name="districts_save", options={"expose"=true})
     *
     * @Permission(actions={Permission::ADD, Permission::EDIT})
     */
    public function save(Request $request, DistrictService $service, RequestHandler $requestHandler)
    {
        $primary = $request->get('id');
        if ($primary) {
            $district = $service->get($primary);
        } else {
            $district = new District();
        }

        $requestHandler->handle($request, $district);
        if (!$requestHandler->isValid()) {
            return new JsonResponse(['status' => 'KO', 'errors' => $requestHandler->getErrors()]);
        }

        $this->commit($district);

        return new JsonResponse(['status' => 'OK']);
    }

    /**
     * @Route("/{id}/delete", methods={"POST"}, name="districts_remove", options={"expose"=true})
     *
     * @Permission(actions=Permission::DELETE)
     */
    public function delete(string $id, DistrictService $service)
    {
        if (!$district = $service->get($id)) {
            throw new NotFoundHttpException();
        }

        $this->remove($district);

        return new JsonResponse(['status' => 'OK']);
    }
}
