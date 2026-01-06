<?php

namespace App\Form;

use App\Entity\Horaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HoraireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('jour', null, [
                'attr' => ['readonly' => true, 'class' => 'fw-bold border-0 bg-transparent'], // Le jour est non-modifiable
                'label' => false
            ])
            ->add('ferme', CheckboxType::class, [
                'label' => 'Fermé',
                'required' => false,
            ])
            ->add('ouvertureMidi', TimeType::class, ['widget' => 'single_text', 'required' => false, 'label' => false])
            ->add('fermetureMidi', TimeType::class, ['widget' => 'single_text', 'required' => false, 'label' => false])
            ->add('ouvertureSoir', TimeType::class, ['widget' => 'single_text', 'required' => false, 'label' => false])
            ->add('fermetureSoir', TimeType::class, ['widget' => 'single_text', 'required' => false, 'label' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Horaire::class]);
    }
}
