<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Repository\CityRepository;
use KejawenLab\Application\SemartHris\Repository\RegionRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CityController extends AdminController
{
    /**
     * @Route("/region/{id}/cities", name="city_by_region", options={"expose"=true})
     *
     * @param string $id
     *
     * @return Response
     */
    public function findByRegionAction(string $id)
    {
        $cities = $this->container->get(CityRepository::class)->findCityByRegion($id);

        return new JsonResponse(['cities' => $cities]);
    }

    /**
     * @Route("/city/filter", name="city_filter")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function filterByRegionAction(Request $request)
    {
        $region = $this->container->get(RegionRepository::class)->find($request->query->get('id'));
        if (!$region) {
            throw new AccessDeniedHttpException();
        }

        $session = $this->get('session');
        $session->set('regionId', $region->getId());

        return $this->redirectToRoute('easyadmin', [
            'action' => 'list',
            'entity' => 'City',
        ]);
    }

    /**
     * @param string $entityClass
     * @param null   $sortDirection
     * @param null   $sortField
     * @param null   $dqlFilter
     *
     * @return QueryBuilder
     */
    protected function createListQueryBuilder($entityClass, $sortDirection = null, $sortField = null, $dqlFilter = null)
    {
        return CityRepository::createListQueryBuilder($this->request, $this->getDoctrine(), $sortField, $sortDirection, $dqlFilter);
    }

    /**
     * @param string $entityClass
     * @param string $searchQuery
     * @param array  $searchableFields
     * @param null   $sortField
     * @param null   $sortDirection
     * @param null   $dqlFilter
     *
     * @return QueryBuilder
     */
    protected function createSearchQueryBuilder($entityClass, $searchQuery, array $searchableFields, $sortField = null, $sortDirection = null, $dqlFilter = null)
    {
        return CityRepository::createQueryBuilderForSearch($this->request, $this->getDoctrine(), $searchQuery, $searchableFields, $sortField, $sortDirection, $dqlFilter);
    }
}
