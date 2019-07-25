<?php
/**
 * This file is part of the Semart HRIS Application.
 *
 * (c) Muhamad Surya Iksanudin <surya.kejawen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Component\Company\Validator;

use KejawenLab\Semart\Skeleton\Component\Company\JobTitleService;
use KejawenLab\Semart\Skeleton\Component\Contract\Company\JobTitleInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SupervisorValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ValidSupervisor) {
            throw new UnexpectedTypeException($constraint, ValidSupervisor::class);
        }

        if (!$value instanceof JobTitleInterface) {
            throw new UnexpectedTypeException($value, JobTitleInterface::class);
        }

        $level = $value->getLevel();
        if (1 < $level && !$value->getParent()) {
            $levels = JobTitleService::getLevels();

            $this->context->buildViolation('label.job_title.must_has_supervisor', ['level' => $levels[$level]])
                ->atPath('parent')
                ->addViolation()
            ;
        }
    }
}
