<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Twig;

use KejawenLab\Application\SemartHris\Component\Address\Model\RegionInterface;
use KejawenLab\Application\SemartHris\Component\Address\Repository\RegionRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RegionExtension extends \Twig_Extension
{
    /**
     * @var RegionRepositoryInterface
     */
    private $repository;

    /**
     * @param RegionRepositoryInterface $repository
     */
    public function __construct(RegionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('semarthris_create_region', [$this, 'getRegion']),
        ];
    }

    /**
     * @param string $id
     *
     * @return RegionInterface|null
     */
    public function getRegion(?string $id): ? RegionInterface
    {
        return $this->repository->find($id);
    }
}
