<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Controller\Admin;

use KejawenLab\Semart\Skeleton\Component\Company\JobTitleService;
use KejawenLab\Semart\Skeleton\Entity\JobTitle;
use KejawenLab\Semart\Skeleton\Pagination\Paginator;
use KejawenLab\Semart\Skeleton\Request\RequestHandler;
use KejawenLab\Semart\Skeleton\Security\Authorization\Permission;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/job-titles")
 *
 * @Permission(menu="JOBTITLE")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class JobTitleController extends AdminController
{
    /**
     * @Route("/", methods={"GET"}, name="job_titles_index", options={"expose"=true})
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
            $jobtitles = $paginator->paginate(JobTitle::class, $page);
            $this->cache($key, $jobtitles);
        } else {
            $jobtitles = $this->cache($key);
        }

        if ($request->isXmlHttpRequest()) {
            $table = $this->renderView('job_title/table-content.html.twig', ['jobTitles' => $jobtitles]);
            $pagination = $this->renderView('job_title/pagination.html.twig', ['jobTitles' => $jobtitles]);

            $response = new JsonResponse([
                'table' => $table,
                'pagination' => $pagination,
                '_cache_id' => $key,
            ]);
        } else {
            $response = $this->render('job_title/index.html.twig', [
                'title' => 'Jabatan',
                'jobTitles' => $jobtitles,
                'levels' => JobTitleService::getLevels(),
                'cacheId' => $key,
            ]);
        }

        return $response;
    }

    /**
     * @Route("/{id}", methods={"GET"}, name="job_titles_detail", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function find(string $id, JobTitleService $service, SerializerInterface $serializer)
    {
        $jobtitle = $service->get($id);
        if (!$jobtitle) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($serializer->serialize($jobtitle, 'json', ['groups' => ['read']]));
    }

    /**
     * @Route("/save", methods={"POST"}, name="job_titles_save", options={"expose"=true})
     *
     * @Permission(actions={Permission::ADD, Permission::EDIT})
     */
    public function save(Request $request, JobTitleService $service, RequestHandler $requestHandler)
    {
        $primary = $request->get('id');
        if ($primary) {
            $jobtitle = $service->get($primary);
        } else {
            $jobtitle = new JobTitle();
        }

        $requestHandler->handle($request, $jobtitle);
        if (!$requestHandler->isValid()) {
            return new JsonResponse(['status' => 'KO', 'errors' => $requestHandler->getErrors()]);
        }

        $this->commit($jobtitle);

        return new JsonResponse(['status' => 'OK']);
    }

    /**
     * @Route("/{id}/delete", methods={"POST"}, name="job_titles_remove", options={"expose"=true})
     *
     * @Permission(actions=Permission::DELETE)
     */
    public function delete(string $id, JobTitleService $service)
    {
        if (!$jobtitle = $service->get($id)) {
            throw new NotFoundHttpException();
        }

        $this->remove($jobtitle);

        return new JsonResponse(['status' => 'OK']);
    }

    /**
     * @Route("/{level}/supervisor", methods={"GET"}, name="job_titles_supervisor", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function levels(int $level, JobTitleService $service, SerializerInterface $serializer)
    {
        return new JsonResponse($serializer->serialize($service->getSupervisors($level), 'json', ['groups' => ['read']]));
    }
}
