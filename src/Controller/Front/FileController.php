<?php

namespace KejawenLab\Application\SemartHris\Controller\Front;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class FileController extends Controller
{
    /**
     * @Route(name="get_file", path="/files/{path}", requirements={"path"=".+"})
     *
     * @Method({"GET"})
     *
     * @param string $path
     *
     * @return Response
     */
    public function getFileAction($path)
    {
        $fullPath = sprintf('%s%s%s', $this->container->getParameter('kernel.project_dir'), $this->container->getParameter('upload_destination'), $path);
        $file = file_get_contents($fullPath);
        if ($file) {
            $response = new Response();

            $response->headers->set('Cache-Control', 'private');
            $response->headers->set('Content-type', mime_content_type($fullPath));
            $response->headers->set('Content-length', filesize($fullPath));

            $response->sendHeaders();
            $response->setContent($file);

            return $response;
        } else {
            throw new NotFoundHttpException();
        }
    }
}
