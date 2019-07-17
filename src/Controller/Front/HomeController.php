<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Controller\Front;

use KejawenLab\Semart\Skeleton\Upload\FileUploadLocator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index()
    {
        return new RedirectResponse($this->generateUrl('home_dashboard'));
    }

    /**
     * @Route("/files/{path}", methods={"GET"}, name="get_files", options={"expose"=true}, requirements={"path"=".+"})
     */
    public function files(string $path, Request $request, FileUploadLocator $fileLocator)
    {
        if ($filePath = $fileLocator->getRealPath($path)) {
            $response = new Response();
            $file = new File($filePath);

            $response->headers->set('Cache-Control', 'private');
            $response->headers->set('Content-type', (string) $file->getMimeType());
            $response->headers->set('Content-length', (string) $file->getSize());

            if ($request->query->get('f')) {
                $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s.%s"', $fileLocator->createUniqueFileName(), $file->getExtension()));
            }

            $response->setContent($fileLocator->getFile($filePath));

            return $response;
        } else {
            throw new NotFoundHttpException();
        }
    }
}
