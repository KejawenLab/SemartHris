<?php

namespace KejawenLab\Application\SemartHris\Twig;

use KejawenLab\Application\SemartHris\Component\Salary\Repository\ComponentRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class SalaryComponentExtension extends \Twig_Extension
{
    /**
     * @var ComponentRepositoryInterface
     */
    private $salaryComponentRepository;

    /**
     * @param ComponentRepositoryInterface $repository
     */
    public function __construct(ComponentRepositoryInterface $repository)
    {
        $this->salaryComponentRepository = $repository;
    }

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('semarthris_component_options', [$this, 'createComponentOptions']),
        ];
    }

    /**
     * @param string $state
     *
     * @return string
     */
    public function createMonthOptions(string $state): string
    {
        $options = '';
        $components = $this->salaryComponentRepository->findByState($state);
        foreach ($components as $component) {
            $options .= sprintf('<option value="%d">%s</option>', $component->getId(), $component);
        }

        return $options;
    }
}
