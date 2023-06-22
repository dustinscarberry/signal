<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\MaintenanceService;
use App\Entity\Service;
use App\Entity\ServiceStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class MaintenanceServiceType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('service', EntityType::class, [
        'class' => Service::class,
        'choice_label' => 'name',
        'choice_value' => function($entity) {
          return $entity ? $entity->getHashId() : '';
        },
        'placeholder' => '',
        'label' => false
      ])
      ->add('status', EntityType::class, [
        'class' => ServiceStatus::class,
        'choice_label' => 'name',
        'choice_value' => function($entity) {
          return $entity ? $entity->getHashId() : '';
        },
        'label' => false
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => MaintenanceService::class
    ]);
  }
}
