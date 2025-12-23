<?php

namespace App\Form;

use App\Entity\Plats;
use App\Entity\Restaurant;
use App\Entity\Stock;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class PlatsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du plat'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description'
            ])
            ->add('prix', MoneyType::class, [
                'label' => 'Prix',
                'currency' => 'EUR'
            ])

            ->add('restaurant', EntityType::class, [
                'class' => Restaurant::class,
                'choice_label' => 'nom',
                'label' => 'Restaurant associé',
                'placeholder' => 'Choisir un restaurant',
            ])

            ->add('image', FileType::class, [
                'label' => 'Photo du plat',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Merci d\'uploader une image valide (JPG, PNG, WEBP)',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plats::class,
        ]);
    }
}
