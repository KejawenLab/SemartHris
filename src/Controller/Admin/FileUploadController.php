<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Controller\Admin;

use KejawenLab\Semart\Collection\Collection;
use KejawenLab\Semart\Skeleton\Upload\UploadHandler;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/files")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class FileUploadController
{
    /**
     * @Route("/upload", methods={"POST"}, name="files_upload", options={"expose"=true})
     */
    public function upload(Request $request, UploadHandler $uploadHandler)
    {
        $files = Collection::collect($request->files->all())
            ->map(static function ($value) use ($uploadHandler) {
                /* @var UploadedFile $value */
                return $uploadHandler->handle($value);
            })
            ->toArray()
        ;

        return new JsonResponse([
            'status' => 'OK',
            'files' => $files,
        ]);
    }
}
