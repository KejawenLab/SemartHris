<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Security\Voter;

use KejawenLab\Application\SemartHris\Util\StringUtil;
use Symfony\Component\Security\Core\Authorization\Voter\Voter as BaseVoter;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
abstract class Voter extends BaseVoter implements VoterInterface
{
    /**
     * @param string $attribute
     * @param mixed  $subject
     *
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array(StringUtil::lowercase($attribute), [self::ACTION_VIEW, self::ACTION_ADD, self::ACTION_EDIT, self::ACTION_DELETE])) {
            return false;
        }

        if (!is_object($subject)) {
            return false;
        }

//        $reflection = $reflection = new \ReflectionObject($subject);
//        if (!$reflection->implementsInterface($this->supportClass())) {
//            return false;
//        }

        return true;
    }

    /**
     * @return string
     */
    abstract protected function supportClass(): string;
}
