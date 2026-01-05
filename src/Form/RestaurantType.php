<?php

namespace App\Form;

use App\Entity\Restaurant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RestaurantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('adresse')
            ->add('ville')
            ->add('codePostal')
            ->add('telephone')
            ->add('nombrePlaces', IntegerType::class, [
                'label' => 'Capacité (nb couverts)',
                'required' => false
            ])
            // 👇 C'est ICI qu'on fait le lien avec ton fichier HoraireType
            ->add('horaires', CollectionType::class, [
                'entry_type' => HoraireType::class, // On appelle ton formulaire Horaire
                'entry_options' => ['label' => false],
                'by_reference' => false, // Indispensable pour que la sauvegarde marche
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Restaurant::class]);
    }
}
