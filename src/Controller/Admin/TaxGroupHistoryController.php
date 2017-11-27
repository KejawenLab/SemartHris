<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use KejawenLab\Application\SemartHris\Form\Manipulator\TaxHistoryManipulator;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class TaxGroupHistoryController extends AdminController
{
<<<<<<< HEAD
=======
    /**
     * @param object $entity
     * @param string $view
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    protected function createEntityFormBuilder($entity, $view)
    {
        $builder = parent::createEntityFormBuilder($entity, $view);

        return $this->container->get(TaxHistoryManipulator::class)->manipulate($builder, $entity);
    }
>>>>>>> f984801a67db4a84a04f88dfa2b5968322d8cdc3
}
