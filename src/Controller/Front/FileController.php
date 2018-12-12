<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Controller\Front;

use KejawenLab\Application\SemartHris\Util\FileUtil;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class FileController extends Controller
{
    /**
     * @Route(name="get_file", path="/files/{path}", requirements={"path"=".+"})
     *
     * @Method({"GET"})
     *
     * @param Request $request
     * @param string  $path
     *
     * @return Response
     */
    public function getFileAction(Request $request, $path)
    {
        $fileUtil = $this->container->get(FileUtil::class);
        if ($file = $fileUtil->getFile($path)) {
            $response = new Response();

            $response->headers->set('Cache-Control', 'private');
            $response->headers->set('Content-type', $fileUtil->getMimeType());
            $response->headers->set('Content-length', $fileUtil->getFileSize());

            if ($request->query->get('force')) {
                $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', date('Y_m_d_H_i_s')));
            }

            $response->setContent($file);

            return $response;
        } else {
            throw new NotFoundHttpException();
        }
    }
}
