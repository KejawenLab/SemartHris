<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use KejawenLab\Application\SemartHris\Form\Manipulator\ReasonManipulator;
use KejawenLab\Application\SemartHris\Repository\ReasonRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ReasonController extends AdminController
{
    /**
     * @Route("/reason/{type}", name="reason_by_type", options={"expose"=true})
     *
     * @param string $type
     *
     * @return Response
     */
    public function findByTypeAction(string $type)
    {
        $reasons = $this->container->get(ReasonRepository::class)->findByType($type);

        return new JsonResponse(['reasons' => $reasons]);
    }

    /**
     * @param object $entity
     * @param string $view
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    protected function createEntityFormBuilder($entity, $view)
    {
        $builder = parent::createEntityFormBuilder($entity, $view);

        return $this->container->get(ReasonManipulator::class)->manipulate($builder, $entity);
    }
}
