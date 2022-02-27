<?php

namespace App\Form;

use App\Entity\Film;
use App\Entity\Salle;
use App\Entity\Seance;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SeanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut', DateTimeType::class, ['placeholder' => [
                'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                'hour' => 'Heure', 'minute' => 'Minute', 'second' => 'Second',
            ], 'attr'=>['class'=>'select_create'],])
            ->add('lang', ChoiceType::class,["choices" => ['VF' => 'VF', 'VOSTFR' => 'VOSTFR',],])
            ->add('film', EntityType::class,["class" => Film::class, "choice_label" => "title"])
            ->add('salle', EntityType::class,["class" => Salle::class, "choice_label" => "numero"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Seance::class,
        ]);
    }
}
