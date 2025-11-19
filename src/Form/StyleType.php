<?php

namespace App\Form;

use App\Entity\Style;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;





class StyleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder

        ->add('nom', TextType::class, [
            'label' => 'Nom',
            'required' => true,
            'attr' => ['maxlength' => 50 , 'minlength' => 3 , 'placeholder'=>'Nom du style']

        ])
        ->add('couleur', ColorType::class, [
            'label' => 'Couleur',
            'required' => true,
        ]);
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Style::class,
        ]);
    }
}
