<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Security\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface as BaseVoter;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface VoterInterface extends BaseVoter
{
    const ACTION_VIEW = 'view';
    const ACTION_EDIT = 'edit';
    const ACTION_ADD = 'add';
    const ACTION_DELETE = 'delete';
}
