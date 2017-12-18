<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Address\Repository;

use KejawenLab\Application\SemartHris\Component\Address\Model\RegionInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface RegionRepositoryInterface
{
    /**
     * @param string $id
     *
     * @return null|RegionInterface
     */
    public function find(?string $id): ? RegionInterface;

    /**
     * @param Request $request
     *
     * @return array
     */
    public function search(Request $request): array;
}
