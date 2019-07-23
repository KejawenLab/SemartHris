<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Controller\Admin;

use KejawenLab\Semart\Skeleton\Component\Education\EducationalTitleService;
use KejawenLab\Semart\Skeleton\Entity\EducationalTitle;
use KejawenLab\Semart\Skeleton\Pagination\Paginator;
use KejawenLab\Semart\Skeleton\Request\RequestHandler;
use KejawenLab\Semart\Skeleton\Security\Authorization\Permission;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/educational-titles")
 *
 * @Permission(menu="EDUCATIONALTITLE")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class EducationalTitleController extends AdminController
{
    /**
     * @Route("/", methods={"GET"}, name="educational_titles_index", options={"expose"=true})
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
            $educationaltitles = $paginator->paginate(EducationalTitle::class, $page);
            $this->cache($key, $educationaltitles);
        } else {
            $educationaltitles = $this->cache($key);
        }

        if ($request->isXmlHttpRequest()) {
            $table = $this->renderView('educational_title/table-content.html.twig', ['educationalTitles' => $educationaltitles]);
            $pagination = $this->renderView('educational_title/pagination.html.twig', ['educationalTitles' => $educationaltitles]);

            $response = new JsonResponse([
                'table' => $table,
                'pagination' => $pagination,
                '_cache_id' => $key,
            ]);
        } else {
            $response = $this->render('educational_title/index.html.twig', [
                'title' => 'Gelar Pendidikan',
                'educationalTitles' => $educationaltitles,
                'cacheId' => $key,
            ]);
        }

        return $response;
    }

    /**
     * @Route("/{id}", methods={"GET"}, name="educational_titles_detail", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function find(string $id, EducationalTitleService $service, SerializerInterface $serializer)
    {
        $educationaltitle = $service->get($id);
        if (!$educationaltitle) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($serializer->serialize($educationaltitle, 'json', ['groups' => ['read']]));
    }

    /**
     * @Route("/save", methods={"POST"}, name="educational_titles_save", options={"expose"=true})
     *
     * @Permission(actions={Permission::ADD, Permission::EDIT})
     */
    public function save(Request $request, EducationalTitleService $service, RequestHandler $requestHandler)
    {
        $primary = $request->get('id');
        if ($primary) {
            $educationaltitle = $service->get($primary);
        } else {
            $educationaltitle = new EducationalTitle();
        }

        $requestHandler->handle($request, $educationaltitle);
        if (!$requestHandler->isValid()) {
            return new JsonResponse(['status' => 'KO', 'errors' => $requestHandler->getErrors()]);
        }

        $this->commit($educationaltitle);

        return new JsonResponse(['status' => 'OK']);
    }

    /**
     * @Route("/{id}/delete", methods={"POST"}, name="educational_titles_remove", options={"expose"=true})
     *
     * @Permission(actions=Permission::DELETE)
     */
    public function delete(string $id, EducationalTitleService $service)
    {
        if (!$educationaltitle = $service->get($id)) {
            throw new NotFoundHttpException();
        }

        $this->remove($educationaltitle);

        return new JsonResponse(['status' => 'OK']);
    }
}
