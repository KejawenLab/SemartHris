<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Controller\Admin;

use KejawenLab\Semart\Skeleton\Component\Education\EducationInstituteService;
use KejawenLab\Semart\Skeleton\Entity\EducationInstitute;
use KejawenLab\Semart\Skeleton\Pagination\Paginator;
use KejawenLab\Semart\Skeleton\Request\RequestHandler;
use KejawenLab\Semart\Skeleton\Security\Authorization\Permission;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/education-institutes")
 *
 * @Permission(menu="EDUCATIONINSTITUTE")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class EducationInstituteController extends AdminController
{
    /**
     * @Route("/", methods={"GET"}, name="education_institutes_index", options={"expose"=true})
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
            $educationinstitutes = $paginator->paginate(EducationInstitute::class, $page);
            $this->cache($key, $educationinstitutes);
        } else {
            $educationinstitutes = $this->cache($key);
        }

        if ($request->isXmlHttpRequest()) {
            $table = $this->renderView('education_institute/table-content.html.twig', ['educationInstitutes' => $educationinstitutes]);
            $pagination = $this->renderView('education_institute/pagination.html.twig', ['educationInstitutes' => $educationinstitutes]);

            $response = new JsonResponse([
                'table' => $table,
                'pagination' => $pagination,
                '_cache_id' => $key,
            ]);
        } else {
            $response = $this->render('education_institute/index.html.twig', [
                'title' => 'Institusi Pendidikan',
                'educationInstitutes' => $educationinstitutes,
                'cacheId' => $key,
            ]);
        }

        return $response;
    }

    /**
     * @Route("/{id}", methods={"GET"}, name="education_institutes_detail", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function find(string $id, EducationInstituteService $service, SerializerInterface $serializer)
    {
        $educationinstitute = $service->get($id);
        if (!$educationinstitute) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($serializer->serialize($educationinstitute, 'json', ['groups' => ['read']]));
    }

    /**
     * @Route("/save", methods={"POST"}, name="education_institutes_save", options={"expose"=true})
     *
     * @Permission(actions={Permission::ADD, Permission::EDIT})
     */
    public function save(Request $request, EducationInstituteService $service, RequestHandler $requestHandler)
    {
        $primary = $request->get('id');
        if ($primary) {
            $educationinstitute = $service->get($primary);
        } else {
            $educationinstitute = new EducationInstitute();
        }

        $requestHandler->handle($request, $educationinstitute);
        if (!$requestHandler->isValid()) {
            return new JsonResponse(['status' => 'KO', 'errors' => $requestHandler->getErrors()]);
        }

        $this->commit($educationinstitute);

        return new JsonResponse(['status' => 'OK']);
    }

    /**
     * @Route("/{id}/delete", methods={"POST"}, name="education_institutes_remove", options={"expose"=true})
     *
     * @Permission(actions=Permission::DELETE)
     */
    public function delete(string $id, EducationInstituteService $service)
    {
        if (!$educationinstitute = $service->get($id)) {
            throw new NotFoundHttpException();
        }

        $this->remove($educationinstitute);

        return new JsonResponse(['status' => 'OK']);
    }
}
