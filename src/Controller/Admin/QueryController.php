<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Controller\Admin;

use KejawenLab\Semart\Skeleton\Query\QueryService;
use KejawenLab\Semart\Skeleton\Security\Authorization\Permission;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/query")
 *
 * @Permission(menu="QUERY")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class QueryController extends AdminController
{
    /**
     * @Route("/runner", methods={"GET"}, name="queries_runner", options={"expose"=true})
     *
     * @Permission(actions=Permission::EDIT)
     */
    public function index(QueryService $service)
    {
        return $this->render('query/index.html.twig', ['connections' => $service->getConnections()]);
    }

    /**
     * @Route("/run", methods={"POST"}, name="queries_run", options={"expose"=true})
     *
     * @Permission(actions=Permission::EDIT)
     */
    public function run(Request $request, QueryService $service)
    {
        $result = $service->runQuery($request->request->get('s'), $request->request->get('c', 'default'));

        return new JsonResponse([
            'status' => $result['status'],
            'result' => $this->renderView('query/result.html.twig', $result),
        ]);
    }

    /**
     * @Route("/tables", methods={"GET"}, name="queries_tables", options={"expose"=true})
     *
     * @Permission(actions=Permission::EDIT)
     */
    public function tables(Request $request, QueryService $service)
    {
        return new JsonResponse([
            'status' => true,
            'result' => $service->getTables($request->query->get('c', 'default')),
        ]);
    }
}
