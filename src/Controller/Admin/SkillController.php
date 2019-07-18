<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Controller\Admin;

use KejawenLab\Semart\Skeleton\Component\Skill\SkillGroupService;
use KejawenLab\Semart\Skeleton\Entity\Skill;
use KejawenLab\Semart\Skeleton\Pagination\Paginator;
use KejawenLab\Semart\Skeleton\Component\Skill\SkillService;
use KejawenLab\Semart\Skeleton\Request\RequestHandler;
use KejawenLab\Semart\Skeleton\Security\Authorization\Permission;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/skills")
 *
 * @Permission(menu="SKILL")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SkillController extends AdminController
{
    /**
     * @Route("/", methods={"GET"}, name="skills_index", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function index(Request $request, Paginator $paginator, SkillGroupService $groupService)
    {
        $page = (int) $request->query->get('p', 1);
        $sort = $request->query->get('s');
        $direction = $request->query->get('d');
        $key = md5(sprintf('%s:%s:%s:%s:%s', __CLASS__, __METHOD__, $page, $sort, $direction));
        if (!$this->isCached($key)) {
            $skills = $paginator->paginate(Skill::class, $page);
            $this->cache($key, $skills);
        } else {
            $skills = $this->cache($key);
        }

        if ($request->isXmlHttpRequest()) {
            $table = $this->renderView('skill/table-content.html.twig', ['skills' => $skills]);
            $pagination = $this->renderView('skill/pagination.html.twig', ['skills' => $skills]);

            $response = new JsonResponse([
                'table' => $table,
                'pagination' => $pagination,
                '_cache_id' => $key,
            ]);
        } else {
            $response = $this->render('skill/index.html.twig', [
                'title' => 'Keahlian',
                'skills' => $skills,
                'skillGroups' => $groupService->getAll(),
                'cacheId' => $key,
            ]);
        }

        return $response;
    }

    /**
     * @Route("/{id}", methods={"GET"}, name="skills_detail", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function find(string $id, SkillService $service, SerializerInterface $serializer)
    {
        $skill = $service->get($id);
        if (!$skill) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($serializer->serialize($skill, 'json', ['groups' => ['read']]));
    }

    /**
     * @Route("/save", methods={"POST"}, name="skills_save", options={"expose"=true})
     *
     * @Permission(actions={Permission::ADD, Permission::EDIT})
     */
    public function save(Request $request, SkillService $service, RequestHandler $requestHandler)
    {
        $primary = $request->get('id');
        if ($primary) {
            $skill = $service->get($primary);
        } else {
            $skill = new Skill();
        }

        $requestHandler->handle($request, $skill);
        if (!$requestHandler->isValid()) {
            return new JsonResponse(['status' => 'KO', 'errors' => $requestHandler->getErrors()]);
        }

        $this->commit($skill);

        return new JsonResponse(['status' => 'OK']);
    }

    /**
     * @Route("/{id}/delete", methods={"POST"}, name="skills_remove", options={"expose"=true})
     *
     * @Permission(actions=Permission::DELETE)
     */
    public function delete(string $id, SkillService $service)
    {
        if (!$skill = $service->get($id)) {
            throw new NotFoundHttpException();
        }

        $this->remove($skill);

        return new JsonResponse(['status' => 'OK']);
    }
}
