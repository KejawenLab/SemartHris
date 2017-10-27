<?php

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController;
use KejawenLab\Application\SemartHris\Component\Setting\Setting;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class SettingController extends AdminController
{
    /**
     * @return Response
     */
    protected function listAction(): Response
    {
        return $this->render('app/setting/list.html.twig', ['settings' => Setting::all()]);
    }

    /**
     * @return Response
     */
    protected function searchAction(): Response
    {
        if ('' === $this->request->query->get('query')) {
            $queryParameters = array_replace($this->request->query->all(), array('action' => 'list', 'query' => null));
            $queryParameters = array_filter($queryParameters);

            return $this->redirect($this->get('router')->generate('easyadmin', $queryParameters));
        }

        return $this->render('app/setting/list.html.twig', ['settings' => Setting::all($this->request->query->get('query'))]);
    }
}
