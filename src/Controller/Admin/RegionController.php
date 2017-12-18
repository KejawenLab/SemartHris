<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use KejawenLab\Application\SemartHris\Repository\RegionRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RegionController extends AdminController
{
    /**
     * @Route("/region/search", name="region_search", options={"expose"=true})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function searchRegionsAction(Request $request)
    {
        $regions = $this->container->get(RegionRepository::class)->search($request);

        return new JsonResponse(['regions' => $regions]);
    }
}
