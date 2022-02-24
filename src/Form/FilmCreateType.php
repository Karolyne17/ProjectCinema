<?php

namespace App\Form;

use App\Entity\Film;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FilmCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add("title", TextType::class, ['label' => 'Titre'])
        ->add("realisateur", TextType::class, ['label' => 'Réalisateur'])
        ->add("genre", TextType::class, ['label' => 'Genre'])
        ->add("duree", NumberType::class, ['label' => 'Durée'])
        ->add("status", TextType::class, ['label' => 'Status'])
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
