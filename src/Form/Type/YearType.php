<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class YearType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $choices = [];
        $yearNow = date('Y');
        for ($i = 0; $i <= 7; ++$i) {
            $year = $yearNow - $i;

            $choices[$year] = $year;
        }

        $resolver->setDefaults([
            'choices' => $choices,
            'data' => $yearNow,
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
