<?php

namespace App\Form;

use App\Models\FiltreArtiste;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltreArtisteType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => "nom de l'artiste",
                'required' => false,
                'attr' => [
                    'placeholder' => 'Saisir le nom de l\'artiste',
                ]
            ]);      
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'method' => 'GET',
            'csrf_protection' => false,
            'data_class' => FiltreArtiste::class
        ]);
    }
}
