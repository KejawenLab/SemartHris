<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Pagination;

use KejawenLab\Semart\Skeleton\Setting\SettingService;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class PaginationExtension extends AbstractExtension
{
    private $request;

    private $settingService;

    public function __construct(RequestStack $requestStack, SettingService $settingService)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->settingService = $settingService;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('start_page_number', [$this, 'startPageNumber']),
        ];
    }

    public function startPageNumber()
    {
        return ((int) $this->settingService->getValue('PER_PAGE') ?: Paginator::PER_PAGE) * ((int) $this->request->query->get('page', 1) - 1) + 1;
    }
}
