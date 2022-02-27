<?php

namespace App\Form;

use App\Entity\Film;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;


class FilmCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add("title", TextType::class, ['label' => 'Titre'])
        ->add("synopsis", TextareaType::class, ['label' => 'synopsis'])
        ->add('image', FileType::class, [
            'label' => 'Image',

            // unmapped means that this field is not associated to any entity property
            'mapped' => false,

            // make it optional so you don't have to re-upload the PDF file
            // every time you edit the Product details
            'required' => false,

            // unmapped fields can't define their validation using annotations
            // in the associated entity, so you can use the PHP constraint classes
            'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                    ],
                    'mimeTypesMessage' => 'Image non valide',
                ])
            ],
        ])
        ->add("realisateur", TextType::class, ['label' => 'Réalisateur'])
        ->add("genre", TextType::class, ['label' => 'Genre'])
        ->add("duree", NumberType::class, ['label' => 'Durée'])
        ->add("status", ChoiceType::class, ["choices" => ["A L'Affiche" => 'affiche', "Archivé" => 'archive',], 'label' => 'Statut'])
        ->add("genre", TextType::class, ['label' => 'Genre'])
        ->add("save", SubmitType::class, ['label' => 'Créer le film'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Film::class,
        ]);
    }
}
