<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Form\EventListener;

use Symfony\Component\Form\FormEvent;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface FieldRemoverInterface
{
    public function remove(FormEvent $event);
}
