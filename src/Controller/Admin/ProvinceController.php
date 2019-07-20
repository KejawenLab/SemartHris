<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Controller\Admin;

use KejawenLab\Semart\Skeleton\Component\Address\ProvinceService;
use KejawenLab\Semart\Skeleton\Entity\Province;
use KejawenLab\Semart\Skeleton\Pagination\Paginator;
use KejawenLab\Semart\Skeleton\Request\RequestHandler;
use KejawenLab\Semart\Skeleton\Security\Authorization\Permission;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/provinces")
 *
 * @Permission(menu="PROVINCE")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ProvinceController extends AdminController
{
    /**
     * @Route("/", methods={"GET"}, name="provinces_index", options={"expose"=true})
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
            $provinces = $paginator->paginate(Province::class, $page);
            $this->cache($key, $provinces);
        } else {
            $provinces = $this->cache($key);
        }

        if ($request->isXmlHttpRequest()) {
            $table = $this->renderView('province/table-content.html.twig', ['provinces' => $provinces]);
            $pagination = $this->renderView('province/pagination.html.twig', ['provinces' => $provinces]);

            $response = new JsonResponse([
                'table' => $table,
                'pagination' => $pagination,
                '_cache_id' => $key,
            ]);
        } else {
            $response = $this->render('province/index.html.twig', [
                'title' => 'Propinsi',
                'provinces' => $provinces,
                'cacheId' => $key,
            ]);
        }

        return $response;
    }

    /**
     * @Route("/{id}", methods={"GET"}, name="provinces_detail", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function find(string $id, ProvinceService $service, SerializerInterface $serializer)
    {
        $province = $service->get($id);
        if (!$province) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($serializer->serialize($province, 'json', ['groups' => ['read']]));
    }

    /**
     * @Route("/save", methods={"POST"}, name="provinces_save", options={"expose"=true})
     *
     * @Permission(actions={Permission::ADD, Permission::EDIT})
     */
    public function save(Request $request, ProvinceService $service, RequestHandler $requestHandler)
    {
        $primary = $request->get('id');
        if ($primary) {
            $province = $service->get($primary);
        } else {
            $province = new Province();
        }

        $requestHandler->handle($request, $province);
        if (!$requestHandler->isValid()) {
            return new JsonResponse(['status' => 'KO', 'errors' => $requestHandler->getErrors()]);
        }

        $this->commit($province);

        return new JsonResponse(['status' => 'OK']);
    }

    /**
     * @Route("/{id}/delete", methods={"POST"}, name="provinces_remove", options={"expose"=true})
     *
     * @Permission(actions=Permission::DELETE)
     */
    public function delete(string $id, ProvinceService $service)
    {
        if (!$province = $service->get($id)) {
            throw new NotFoundHttpException();
        }

        $this->remove($province);

        return new JsonResponse(['status' => 'OK']);
    }
}
