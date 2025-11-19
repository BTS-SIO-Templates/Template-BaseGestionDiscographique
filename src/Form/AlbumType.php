<?php

namespace App\Form;

use Faker\Core\File;
use App\Entity\Album;
use App\Entity\Style;
use App\Entity\Artiste;
use App\Form\MorceauType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'label'=>"Nom de l'album",
                'attr'=>[
                    "placeholder"=>"Saisir le nom d'un album",
                    "required"=>true,
                    "minlength"=>2,
                    "maxlength"=>50
                ]
            ])
            ->add('date', TextType::class,[
                'label'=>"Date de l'album",
                'attr'=>[
                        "placeholder"=>"Saisir la date de l'album",
                        "ranged"=>true,
                        "min"=>1900,
                        "max"=>2099
                    ]
                
                ])
            ->add('artiste', EntityType::class,[
                'label'=>"Nom de l'artiste",
                'class'=>Artiste::class,
                'choice_label'=>"nom",
                'attr'=>[
                    "placeholder"=>"Saisir le nom de l'artiste",
                    "required"=>true,
                ]
            ])
            ->add('styles', EntityType::class, [
                'multiple' => true,
                'expanded' => false,
                'by_reference' => false,
                'class' => Style::class,
                'choice_label' => 'nom', // le nom affiché dans le menu
                'placeholder' => 'Choisissez un style',
                'required' => true,
                'attr' => [
                    'class' => 'selectStyles'// Ajoutez une classe CSS pour cibler avec Select2

                ],
            ])
            ->add("morceaux", CollectionType::class, [
                'entry_type' => MorceauType::class,
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => true,
                'prototype' => true,
            ])
            ->add('imageFile',FileType::class,[
                'mapped'=>false,
                'label'=>"Image de l'album",
                'required'=>false,
                'attr'=> [
                    'accept'=>".jpg,.jpeg,.png",
                ],
                'row_attr'=>[
                    'class'=>'d-none'
                ],
                'constraints' => [
                        new Image([
                            'maxSize' => '4k',
                            'maxSizeMessage' => 'Le fichier est trop volumineux',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                            ],
                        ])
                ]
            ])
            ->add('image', HiddenType::class);

            
        }

            
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Album::class,
        ]);
    }
}
