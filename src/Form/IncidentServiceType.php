<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\IncidentService;
use App\Entity\Service;
use App\Entity\ServiceStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class IncidentServiceType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('service', EntityType::class, [
        'class' => Service::class,
        'choice_label' => 'name',
        'placeholder' => '',
        'label' => false
      ])
      ->add('status', EntityType::class, [
        'class' => ServiceStatus::class,
        'choice_label' => 'name',
        'label' => false
      ]);
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'data_class' => IncidentService::class
    ]);
  }
}
