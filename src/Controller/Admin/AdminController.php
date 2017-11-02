<?php

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as EasyAdmin;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class AdminController extends EasyAdmin
{
    const DEFAULT_ROLE = 'ROLE_EMPLOYEE';

    /**
     * @param Request $request
     */
    protected function initialize(Request $request)
    {
        parent::initialize($request);

        $this->denyAccessUnlessGranted($this->entity['role'] ?? self::DEFAULT_ROLE);
        $action = $request->query->get('action', 'list');
        $this->denyAccessUnlessGranted($this->entity[$action]['role'] ?? self::DEFAULT_ROLE);
    }
}
