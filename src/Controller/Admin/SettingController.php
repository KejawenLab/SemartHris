<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Exception\NoEntitiesConfiguredException;
use EasyCorp\Bundle\EasyAdminBundle\Exception\UndefinedEntityException;
use KejawenLab\Application\SemartHris\Component\Setting\Service\Setting;
use KejawenLab\Application\SemartHris\Entity\Region;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SettingController extends AdminController
{
    /**
     * @return Response
     */
    protected function listAction(): Response
    {
        return $this->render('app/setting/list.html.twig', ['settings' => $this->container->get(Setting::class)->all()]);
    }

    /**
     * @return Response
     */
    protected function searchAction(): Response
    {
        if ('' === $this->request->query->get('query')) {
            $queryParameters = array_replace($this->request->query->all(), ['action' => 'list', 'query' => null]);
            $queryParameters = array_filter($queryParameters);

            return $this->redirect($this->get('router')->generate('easyadmin', $queryParameters));
        }

        return $this->render($this->entity['templates']['list'], ['settings' => $this->container->get(Setting::class)->all($this->request->query->get('query'))]);
    }

    /**
     * @return Response
     */
    protected function editAction(): Response
    {
        $id = $this->request->query->get('id');
        $easyadmin = $this->request->attributes->get('easyadmin');
        $entity = $easyadmin['item'];
        $fields = $this->entity['edit']['fields'];

        /**
         * This created just because the form is mapped to Region object.
         */
        $cheat = new Region();
        $cheat->setName($id);

        $editForm = $this->executeDynamicMethod('create<EntityName>EditForm', [$cheat, $fields]);
        $deleteForm = $this->createDeleteForm($this->entity['name'], $id);

        $editForm->handleRequest($this->request);
        if ($editForm->isSubmitted()) {
            $setting = $this->container->get(Setting::class);
            if ($value = $cheat->getId()) {
                $setting->update($cheat->getName(), $value);
            }

            return $this->redirectToReferrer();
        }

        return $this->render($this->entity['templates']['edit'], [
            'id' => $id,
            'form' => $editForm->createView(),
            'entity_fields' => $fields,
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    protected function initialize(Request $request)
    {
        $this->config = $this->get('easyadmin.config.manager')->getBackendConfig();

        if (0 === count($this->config['entities'])) {
            throw new NoEntitiesConfiguredException();
        }

        // this condition happens when accessing the backend homepage and before
        // redirecting to the default page set as the homepage
        if (null === $entityName = $request->query->get('entity')) {
            return;
        }

        if (!isset($this->config['entities'][$entityName])) {
            throw new UndefinedEntityException(['entity_name' => $entityName]);
        }

        $this->entity = $this->get('easyadmin.config.manager')->getEntityConfig($entityName);

        $this->request = $request;

        $this->request->attributes->set('easyadmin', [
            'entity' => $entityName,
            'view' => $this->request->query->get('action', 'list'),
            'item' => $this->request->query->get('id'),
        ]);
    }
}
