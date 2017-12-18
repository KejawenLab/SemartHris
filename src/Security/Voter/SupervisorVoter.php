<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Security\Voter;

use KejawenLab\Application\SemartHris\Component\Employee\Model\Superviseable;
use KejawenLab\Application\SemartHris\Component\Employee\Service\SupervisorChecker;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SupervisorVoter extends Voter
{
    /**
     * @var SupervisorChecker
     */
    private $supervisorChecker;

    /**
     * @param SupervisorChecker $supervisorChecker
     */
    public function __construct(SupervisorChecker $supervisorChecker)
    {
        $this->supervisorChecker = $supervisorChecker;
    }

    /**
     * @param string         $attribute
     * @param mixed          $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof Superviseable) {
            return false;
        }

        switch ($attribute) {
            /*
             * Supervisor check is needed only for view and edit
             */
            case self::ACTION_ADD:
            case self::ACTION_DELETE:
                return false;
                break;
            case self::ACTION_VIEW:
            case self::ACTION_EDIT:
                return $this->supervisorChecker->isAllowToSupervise($user, $subject);
                break;
        }

        throw new \LogicException('This code should not be reached!');
    }

    /**
     * @return string
     */
    protected function supportClass(): string
    {
        return Superviseable::class;
    }
}
