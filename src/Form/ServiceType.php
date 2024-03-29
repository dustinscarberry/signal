<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Service;
use App\Entity\ServiceCategory;
use App\Entity\ServiceStatus;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ServiceType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('name', TextType::class)
      ->add('description', TextType::class)
      ->add('serviceCategory', EntityType::class, [
        'class' => ServiceCategory::class,
        'choice_label' => 'name',
        'choice_value' => function($entity) {
          return $entity ? $entity->getHashId() : '';
        }
      ])
      ->add('status', EntityType::class, [
        'class' => ServiceStatus::class,
        'choice_label' => 'name',
        'choice_value' => function($entity) {
          return $entity ? $entity->getHashId() : '';
        }
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Service::class
    ]);
  }
}
