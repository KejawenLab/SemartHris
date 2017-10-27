<?php

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController;
use KejawenLab\Application\SemartHris\Form\Manipulator\ContractManipulator;
use KejawenLab\Application\SemartHris\Repository\ContractRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class ContractController extends AdminController
{
    /**
     * @Route("/contract/tags", name="contract_tags", options={"expose"=true})
     *
     * @return Response
     */
    public function findAllTagsAction()
    {
        $result = [];
        $tags = $this->container->get(ContractRepository::class)->findAllTags();
        foreach ($tags as $tag) {
            foreach ($tag as $item) {
                $result = array_merge(array_values($item), $result);
            }
        }

        return new JsonResponse(['tags' => $result]);
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

        return $this->container->get(ContractManipulator::class)->manipulate($builder, $entity);
    }
}
