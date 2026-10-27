<?php

namespace App\Form;

use App\Entity\Forecast;
use App\Entity\Location;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForecastType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'choice',
                'attr' => [
                    'placeholder' => 'Date',
                ]
            ])
            ->add('temperature', null, [
                'attr' => [
                    'placeholder' => 'Temperature',
                ]
            ])
            ->add('humidity', null, [
                'attr' => [
                    'placeholder' => 'Humidity',
                ]
            ])
            ->add('windSpeed', null, [
                'attr' => [
                    'placeholder' => 'Wind speed',
                ]
            ])
            ->add('rain', null, [
                'attr' => [
                    'placeholder' => 'Rain',
                ]
            ])
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Forecast::class,
        ]);
    }
}
