<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\MaintenanceStatus;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MaintenanceStatusType extends AbstractType
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
          'Offline' => 'offline',
          'Progress' => 'progress',
          'Future' => 'future'
        ]
      ]);
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'data_class' => MaintenanceStatus::class
    ]);
  }
}
