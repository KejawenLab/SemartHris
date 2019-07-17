<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Security\Authorization;

use KejawenLab\Semart\Skeleton\Contract\Service\ServiceInterface;
use KejawenLab\Semart\Skeleton\Entity\Group;
use KejawenLab\Semart\Skeleton\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class OwnershipChecker
{
    private $token;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->token = $tokenStorage->getToken();
    }

    public function isOwner(string $id, ServiceInterface $service): bool
    {
        if (!$this->token) {
            return false;
        }

        /** @var User $user */
        $user = $this->token->getUser();
        $group = $user->getGroup();

        if (!$group) {
            return false;
        }

        if (Group::SUPER_ADMINISTRATOR_CODE === $group->getCode()) {
            return true;
        }

        if (!$data = $service->get($id)) {
            return false;
        }

        if (!method_exists($data, 'getCreatedBy')) {
            return false;
        }

        if ($data->getCreatedBy() === $user->getUsername()) {
            return true;
        }

        return false;
    }
}
