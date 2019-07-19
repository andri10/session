<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\Stagiaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Intitule')
            ->add('Presentation')
            ->add('NbPlace', IntegerType::class, [
                "attr" => [
                    "maxNb" => null
                ]
            ])
            ->add('DateDebut')
            ->add('dateFin')
            // ->add('ressources')
            ->add('stagiaires', CollectionType::class, [
                'entry_type' => EntityType::class,
                'entry_options' => [
                    'label' => "choisir :",
                    'class' => Stagiaire::class,
                    'choice_label' => "nomPrenom"
                ],
                'allow_add' => true,
                'allow_delete' => true,
            ])
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
