<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\ServiceStatus;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ServiceStatusType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('name', TextType::class)
      ->add('type', ChoiceType::class, [
        'choices' => [
          'Ok' => 'ok',
          'Issues' => 'issue',
          'Error' => 'error',
          'Unknown' => 'unknown',
          'Offline' => 'offline'
        ]
      ])
      ->add('metricValue', IntegerType::class, [
        'attr' => [
          'min' => 0,
          'max' => 100
        ]
      ]);
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'data_class' => ServiceStatus::class
    ]);
  }
}
