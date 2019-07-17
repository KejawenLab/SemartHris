<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Security\Service;

use KejawenLab\Semart\Skeleton\Entity\User;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SecurityExtension extends AbstractExtension
{
    private $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_super_admin', [$this, 'isSuperAdmin']),
        ];
    }

    public function isSuperAdmin(User $user): bool
    {
        return $this->groupService->isSuperAdmin($user);
    }
}
