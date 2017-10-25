<?php

namespace KejawenLab\Application\SemartHris\Security\Voter;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Model\Superviseable;
use KejawenLab\Application\SemartHris\Component\Employee\Service\EmployeeSupervisorChecker;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class SupervisorVoter extends Voter
{
    /**
     * @var EmployeeSupervisorChecker
     */
    private $supervisorChecker;

    /**
     * @param EmployeeSupervisorChecker $supervisorChecker
     */
    public function __construct(EmployeeSupervisorChecker $supervisorChecker)
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
        if (!$user instanceof EmployeeInterface) {
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
