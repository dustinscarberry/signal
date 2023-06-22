<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\CustomMetricDatapoint;
use App\Entity\CustomMetric;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CustomMetricDatapointType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('metric', EntityType::class, [
        'class' => CustomMetric::class,
        'choice_label' => 'name',
        'choice_value' => function($entity) {
          return $entity ? $entity->getHashId() : '';
        }
      ])
      ->add('value', IntegerType::class)
      ->add('created', IntegerType::class, [
        'required' => false
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => CustomMetricDatapoint::class
    ]);
  }
}
