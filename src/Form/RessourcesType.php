<?php

namespace App\Form;

use App\Entity\Salle;
use App\Entity\Ressource;
use App\Form\PossederType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RessourcesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('posseders', CollectionType::class, [
                'label' => false,
                'entry_type' => PossederType::class,
                'entry_options' => [
                    'label' => "Ressource et qte utilisée :"
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ])

            ->add('valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Salle::class,
        ]);
    }
}
