<?php

namespace KejawenLab\Application\SemarHris\DataTransformer;

use KejawenLab\Application\SemarHris\Component\Address\Model\CityInterface;
use KejawenLab\Application\SemarHris\Component\Address\Repository\CityRepositoryInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@personahris.com>
 */
final class CityTransformer implements DataTransformerInterface
{
    /**
     * @var CityRepositoryInterface
     */
    private $cityRepository;

    /**
     * @param CityRepositoryInterface $cityRepository
     */
    public function __construct(CityRepositoryInterface $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    /**
     * @param object $city
     *
     * @return string
     */
    public function transform($city): string
    {
        if (null === $city) {
            return '';
        }

        return $city->getId();
    }

    /**
     * @param string $cityId
     *
     * @return null|CityInterface
     */
    public function reverseTransform($cityId)
    {
        if (!$cityId) {
            return null;
        }

        $city = $this->cityRepository->find($cityId);
        if (null === $city) {
            throw new TransformationFailedException(sprintf('City with id %s is not exist.', $cityId));
        }

        return $city;
    }
}
