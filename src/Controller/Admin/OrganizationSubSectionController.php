<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Controller\Admin;

use KejawenLab\Semart\Skeleton\Component\Contract\Organization\OrganizationInterface;
use KejawenLab\Semart\Skeleton\Security\Authorization\Permission;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sub_sections")
 *
 * @Permission(menu="SUBSECTION")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class OrganizationSubSectionController extends AdminController
{
    /**
     * @Route("/", methods={"GET"}, name="sub_sections_index", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function index()
    {
        return $this->redirectToRoute('organizations_index', ['l' => OrganizationInterface::LEVEL_SUB_SECTION]);
    }
}
