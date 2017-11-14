<?php

namespace KejawenLab\Application\SemartHris\Form\Type;

use KejawenLab\Application\SemartHris\Util\MonthUtil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class MonthType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => array_flip(MonthUtil::getMonths()),
            'data' => date('n'),
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
