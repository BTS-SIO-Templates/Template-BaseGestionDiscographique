<?php

namespace App\Form;

use App\Entity\Style;
use App\Entity\Artiste;
use App\Models\FiltreAlbum;
use App\Repository\ArtisteRepository;
use App\Repository\StyleRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FiltreAlbumType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => "nom de l'album",
                'required' => false,
                'attr' => [
                    'placeholder' => 'Saisir le nom de l\'album',
                ]
            ])
            ->add('artiste', EntityType::class,[
                'class'=>Artiste::class,
                'query_builder'=>function(ArtisteRepository $repo){
                    return $repo->listeArtisteSimple();
                },
                'choice_label'=>'nom',
                'label'=>"nom de l'artiste",
                'required'=>false,
            ])
            ->add('styles', EntityType::class, [
                'class' => Style::class,
                'query_builder' => function (StyleRepository $repo) {
                    return $repo->listeStylesSimple();
                },
                'choice_label' => 'nom',
                'label' => "Styles musicaux",
                'required' => false,
                'multiple' => true,
                'attr' => [
                    'class' => 'selectStyles',

                ],]);
        
            

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'method' => 'GET',
            'csrf_protection' => false,
            'data_class' => FiltreAlbum::class
        ]);
    }
}
