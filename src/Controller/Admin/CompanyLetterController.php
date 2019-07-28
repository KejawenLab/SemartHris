<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Controller\Admin;

use KejawenLab\Semart\Skeleton\Component\Company\CompanyLetterService;
use KejawenLab\Semart\Skeleton\Entity\CompanyLetter;
use KejawenLab\Semart\Skeleton\Pagination\Paginator;
use KejawenLab\Semart\Skeleton\Request\RequestHandler;
use KejawenLab\Semart\Skeleton\Security\Authorization\Permission;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/company-letters")
 *
 * @Permission(menu="COMPANYLETTER")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CompanyLetterController extends AdminController
{
    /**
     * @Route("/", methods={"GET"}, name="company_letters_index", options={"expose"=true})
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
            $companyletters = $paginator->paginate(CompanyLetter::class, $page);
            $this->cache($key, $companyletters);
        } else {
            $companyletters = $this->cache($key);
        }

        if ($request->isXmlHttpRequest()) {
            $table = $this->renderView('company_letter/table-content.html.twig', ['companyLetters' => $companyletters]);
            $pagination = $this->renderView('company_letter/pagination.html.twig', ['companyLetters' => $companyletters]);

            $response = new JsonResponse([
                'table' => $table,
                'pagination' => $pagination,
                '_cache_id' => $key,
            ]);
        } else {
            $response = $this->render('company_letter/index.html.twig', [
                'title' => 'Surat Perusahaan',
                'companyLetters' => $companyletters,
                'cacheId' => $key,
            ]);
        }

        return $response;
    }

    /**
     * @Route("/{id}", methods={"GET"}, name="company_letters_detail", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function find(string $id, CompanyLetterService $service, SerializerInterface $serializer)
    {
        $companyletter = $service->get($id);
        if (!$companyletter) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($serializer->serialize($companyletter, 'json', ['groups' => ['read']]));
    }

    /**
     * @Route("/save", methods={"POST"}, name="company_letters_save", options={"expose"=true})
     *
     * @Permission(actions={Permission::ADD, Permission::EDIT})
     */
    public function save(Request $request, CompanyLetterService $service, RequestHandler $requestHandler)
    {
        $primary = $request->get('id');
        if ($primary) {
            $companyletter = $service->get($primary);
        } else {
            $companyletter = new CompanyLetter();
        }

        $requestHandler->handle($request, $companyletter);
        if (!$requestHandler->isValid()) {
            return new JsonResponse(['status' => 'KO', 'errors' => $requestHandler->getErrors()]);
        }

        $this->commit($companyletter);

        return new JsonResponse(['status' => 'OK']);
    }

    /**
     * @Route("/{id}/delete", methods={"POST"}, name="company_letters_remove", options={"expose"=true})
     *
     * @Permission(actions=Permission::DELETE)
     */
    public function delete(string $id, CompanyLetterService $service)
    {
        if (!$companyletter = $service->get($id)) {
            throw new NotFoundHttpException();
        }

        $this->remove($companyletter);

        return new JsonResponse(['status' => 'OK']);
    }
}
