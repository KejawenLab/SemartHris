<?php

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController;
use KejawenLab\Application\SemartHris\Form\Manipulator\ReasonManipulator;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class ReasonController extends AdminController
{
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
