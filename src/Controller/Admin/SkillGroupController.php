<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Controller\Admin;

use KejawenLab\Semart\Skeleton\Component\Skill\SkillGroupService;
use KejawenLab\Semart\Skeleton\Entity\SkillGroup;
use KejawenLab\Semart\Skeleton\Pagination\Paginator;
use KejawenLab\Semart\Skeleton\Request\RequestHandler;
use KejawenLab\Semart\Skeleton\Security\Authorization\Permission;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/skill-groups")
 *
 * @Permission(menu="SKILLGROUP")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SkillGroupController extends AdminController
{
    /**
     * @Route("/", methods={"GET"}, name="skill_groups_index", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function index(Request $request, Paginator $paginator, SkillGroupService $service)
    {
        $page = (int) $request->query->get('p', 1);
        $sort = $request->query->get('s');
        $direction = $request->query->get('d');
        $key = md5(sprintf('%s:%s:%s:%s:%s', __CLASS__, __METHOD__, $page, $sort, $direction));
        if (!$this->isCached($key)) {
            $skillgroups = $paginator->paginate(SkillGroup::class, $page);
            $this->cache($key, $skillgroups);
        } else {
            $skillgroups = $this->cache($key);
        }

        if ($request->isXmlHttpRequest()) {
            $table = $this->renderView('skill_group/table-content.html.twig', ['skillGroups' => $skillgroups]);
            $pagination = $this->renderView('skill_group/pagination.html.twig', ['skillGroups' => $skillgroups]);

            $response = new JsonResponse([
                'table' => $table,
                'pagination' => $pagination,
                '_cache_id' => $key,
            ]);
        } else {
            $response = $this->render('skill_group/index.html.twig', [
                'title' => 'Grup Keahlian',
                'skillGroups' => $skillgroups,
                'parents' => $service->getAll(),
                'cacheId' => $key,
            ]);
        }

        return $response;
    }

    /**
     * @Route("/{id}", methods={"GET"}, name="skill_groups_detail", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function find(string $id, SkillGroupService $service, SerializerInterface $serializer)
    {
        $skillgroup = $service->get($id);
        if (!$skillgroup) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($serializer->serialize($skillgroup, 'json', ['groups' => ['read']]));
    }

    /**
     * @Route("/save", methods={"POST"}, name="skill_groups_save", options={"expose"=true})
     *
     * @Permission(actions={Permission::ADD, Permission::EDIT})
     */
    public function save(Request $request, SkillGroupService $service, RequestHandler $requestHandler)
    {
        $primary = $request->get('id');
        if ($primary) {
            $skillgroup = $service->get($primary);
        } else {
            $skillgroup = new SkillGroup();
        }

        $requestHandler->handle($request, $skillgroup);
        if (!$requestHandler->isValid()) {
            return new JsonResponse(['status' => 'KO', 'errors' => $requestHandler->getErrors()]);
        }

        $this->commit($skillgroup);

        return new JsonResponse(['status' => 'OK']);
    }

    /**
     * @Route("/{id}/delete", methods={"POST"}, name="skill_groups_remove", options={"expose"=true})
     *
     * @Permission(actions=Permission::DELETE)
     */
    public function delete(string $id, SkillGroupService $service)
    {
        if (!$skillgroup = $service->get($id)) {
            throw new NotFoundHttpException();
        }

        $this->remove($skillgroup);

        return new JsonResponse(['status' => 'OK']);
    }
}
