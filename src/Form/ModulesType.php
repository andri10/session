<?php namespace App\Form;


use App\Form\DureeType;
use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ModulesType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('durees', CollectionType::class, [
                    'label' => false,
                    'entry_type' => DureeType::class,
                    'entry_options' => [
                        'label' => "Module et durée :"
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
            'data_class' => Formation::class,
        ]);
    }

}