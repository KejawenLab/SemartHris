<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use KejawenLab\Application\SemartHris\Repository\JobTitleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class JobTitleController extends AdminController
{
    /**
     * @Route("/joblevel/{id}/jobtitles", name="jobtitle_by_joblevel", options={"expose"=true})
     *
     * @param string $id
     *
     * @return Response
     */
    public function findByCompanyAction(string $id)
    {
        $jobtitles = $this->container->get(JobTitleRepository::class)->findByJobLevel($id);

        return new JsonResponse(['jobtitles' => $jobtitles]);
    }
}
