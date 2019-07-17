<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Controller\Admin;

use KejawenLab\Semart\Skeleton\Entity\Setting;
use KejawenLab\Semart\Skeleton\Pagination\Paginator;
use KejawenLab\Semart\Skeleton\Request\RequestHandler;
use KejawenLab\Semart\Skeleton\Security\Authorization\Permission;
use KejawenLab\Semart\Skeleton\Setting\SettingService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/settings")
 *
 * @Permission(menu="SETTING")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SettingController extends AdminController
{
    /**
     * @Route("/", methods={"GET"}, name="settings_index", options={"expose"=true})
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
            $settings = $paginator->paginate(Setting::class, $page);
            $this->cache($key, $settings);
        } else {
            $settings = $this->cache($key);
        }

        if ($request->isXmlHttpRequest()) {
            $table = $this->renderView('setting/table-content.html.twig', ['settings' => $settings]);
            $pagination = $this->renderView('setting/pagination.html.twig', ['settings' => $settings]);

            $response = new JsonResponse([
                'table' => $table,
                'pagination' => $pagination,
                '_cache_id' => $key,
            ]);
        } else {
            $response = $this->render('setting/index.html.twig', [
                'title' => 'Setting',
                'settings' => $settings,
                'cacheId' => $key,
            ]);
        }

        return $response;
    }

    /**
     * @Route("/{id}", methods={"GET"}, name="settings_detail", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function find(string $id, SettingService $service, SerializerInterface $serializer)
    {
        $setting = $service->get($id);
        if (!$setting) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($serializer->serialize($setting, 'json', ['groups' => ['read']]));
    }

    /**
     * @Route("/save", methods={"POST"}, name="settings_save", options={"expose"=true})
     *
     * @Permission(actions={Permission::ADD, Permission::EDIT})
     */
    public function save(Request $request, SettingService $service, RequestHandler $requestHandler)
    {
        $primary = $request->get('id');
        if ($primary) {
            $setting = $service->get($primary);
        } else {
            $setting = new Setting();
        }

        if (!$setting) {
            throw new NotFoundHttpException();
        }

        $requestHandler->handle($request, $setting);
        if (!$requestHandler->isValid()) {
            return new JsonResponse(['status' => 'KO', 'errors' => $requestHandler->getErrors()]);
        }

        $this->commit($setting);

        return new JsonResponse(['status' => 'OK']);
    }

    /**
     * @Route("/{id}/delete", methods={"POST"}, name="settings_remove", options={"expose"=true})
     *
     * @Permission(actions=Permission::DELETE)
     */
    public function delete(string $id, SettingService $service)
    {
        if (!$setting = $service->get($id)) {
            throw new NotFoundHttpException();
        }

        $this->remove($setting);

        return new JsonResponse(['status' => 'OK']);
    }
}
