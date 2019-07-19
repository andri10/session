<?php

namespace App\Form;

use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Intitule')
            ->add('Presentation')
            ->add('NbPlace')
            ->add('DateDebut')
            ->add('dateFin')
            // ->add('ressources')
            // ->add('stagiaires')
            ->add('submit', SubmitType::class, ['label' => 'Valider', 'attr' => ['class' => 'btn-info']])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
