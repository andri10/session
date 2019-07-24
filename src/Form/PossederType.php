<?php

namespace App\Form;

use App\Entity\Posseder;
use App\Entity\Ressource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PossederType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ressource', EntityType::class, [
                'class' => Ressource::class,
                'choice_label' => 'intitule',
                'label' => false,
            ])
            ->add('quantiteUse')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Posseder::class,
        ]);
    }
}
