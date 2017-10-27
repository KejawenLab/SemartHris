<?php

namespace KejawenLab\Application\SemartHris\Controller\Front;

use KejawenLab\Application\SemartHris\Util\FileUtil;
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
        $fileUtil = $this->container->get(FileUtil::class);
        if ($file = $fileUtil->getFile($path)) {
            $response = new Response();

            $response->headers->set('Cache-Control', 'private');
            $response->headers->set('Content-type', $fileUtil->getFileSize());
            $response->headers->set('Content-length', $fileUtil->getFileSize());

            $response->sendHeaders();
            $response->setContent($file);

            return $response;
        } else {
            throw new NotFoundHttpException();
        }
    }
}
