<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Util;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class FileUtil
{
    /**
     * @var string
     */
    private $folderStorage;

    /**
     * @var string
     */
    private $mimeType;

    /**
     * @var int
     */
    private $fileSize;

    /**
     * @param string $folderStorage
     */
    public function __construct(string $folderStorage)
    {
        $this->folderStorage = rtrim($folderStorage, '/');
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function getFile(string $path): string
    {
        $path = sprintf('%s/%s', $this->folderStorage, $path);
        try {
            $file = file_get_contents($path);
        } catch (\Exception $e) {
            throw new FileNotFoundException();
        }

        $this->mimeType = mime_content_type($path);
        $this->fileSize = filesize($path);

        return $file;
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * @return int
     */
    public function getFileSize(): int
    {
        return $this->fileSize;
    }
}
