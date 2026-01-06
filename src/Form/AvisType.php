<?php

namespace App\Form;

use App\Entity\Avis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note', ChoiceType::class, [
                'label' => 'Votre note',
                'choices'  => [
                    '⭐⭐⭐⭐⭐ Excellent' => 5,
                    '⭐⭐⭐⭐ Très bon' => 4,
                    '⭐⭐⭐ Bon' => 3,
                    '⭐⭐ Moyen' => 2,
                    '⭐ Mauvais' => 1,
                ],
                'expanded' => true,
                'attr' => ['class' => 'd-flex justify-content-between mb-3 gap-3']
            ])
            ->add('commentaire', TextareaType::class, [
                'label' => 'Votre avis (optionnel)',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                    'placeholder' => 'Dites-nous ce que vous avez aimé...'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Publier mon avis',
                'attr' => ['class' => 'btn btn-success mt-3 w-100']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avis::class,
        ]);
    }
}
