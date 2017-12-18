<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use KejawenLab\Application\SemartHris\Component\Contract\ContractType;
use KejawenLab\Application\SemartHris\Form\Manipulator\ContractManipulator;
use KejawenLab\Application\SemartHris\Repository\ContractRepository;
use KejawenLab\Application\SemartHris\Util\StringUtil;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ContractController extends AdminController
{
    /**
     * @Route("/contract/tags", name="contract_tags", options={"expose"=true})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function searchTagsAction(Request $request)
    {
        $result = [];
        $tags = $this->container->get(ContractRepository::class)->search($request);
        foreach ($tags as $tag) {
            foreach ($tag as $item) {
                foreach ($item as $value) {
                    if (false !== strpos($value, StringUtil::uppercase($request->query->get('search', '')))) {
                        $result[] = $value;
                    }
                }
            }
        }

        return new JsonResponse(['tags' => $result]);
    }

    /**
     * @Route("/contract/employee", name="contract_employee", options={"expose"=true})
     *
     * @return Response
     */
    public function findEmployeeContractAction()
    {
        return new JsonResponse(['contracts' => $this->container->get(ContractRepository::class)->findByType(ContractType::CONTRACT_EMPLOYEE)]);
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
