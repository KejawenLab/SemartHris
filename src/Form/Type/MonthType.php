<?php

namespace KejawenLab\Application\SemartHris\Form\Type;

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
        $choices = [
            'Januari' => 1,
            'Februari' => 2,
            'Maret' => 3,
            'April' => 4,
            'Mei' => 5,
            'Juni' => 6,
            'Juli' => 7,
            'Agustus' => 8,
            'September' => 9,
            'Oktober' => 10,
            'November' => 11,
            'Desember' => 12,
        ];

        $resolver->setDefaults([
            'choices' => $choices,
            'data' => date('n'),
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
