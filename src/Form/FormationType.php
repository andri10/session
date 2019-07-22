<?php

namespace App\Form;

use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('intitule')
            ->add('presentation')
            ->add('nbPlace'/* , IntegerType::class, [
                "attr" => [
                    "maxNb" => null
                ]
            ] */)
            ->add('dateDebut', DateType::class, [
            'widget' => 'single_text'
            ])
            ->add('dateFin', DateType::class, [
            'widget' => 'single_text'
            ])
            
            /* ->add('stagiaires', CollectionType::class, [
                'entry_type' => EntityType::class,
                'entry_options' => [
                    'label' => "choisir :",
                    'class' => Stagiaire::class,
                    'choice_label' => "nomPrenom"
                ],
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('ressources') */
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
