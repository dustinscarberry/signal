<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use App\Form\DataTransformer\TimestampToDateTimeStringTransformer;
use App\Entity\Maintenance;
use App\Entity\MaintenanceStatus;
use App\Form\MaintenanceServiceType;
use App\Form\MaintenanceUpdateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class MaintenanceType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('name', TextType::class)
      ->add('purpose', TextareaType::class)
      ->add('visibility', CheckboxType::class, [
        'required' => false
      ])
      ->add('scheduledFor', TextType::class)
      ->add('anticipatedEnd', TextType::class, [
        'required' => false
      ])
      ->add('status', EntityType::class, [
        'class' => MaintenanceStatus::class,
        'choice_label' => 'name',
        'choice_value' => function($entity) {
          return $entity ? $entity->getHashId() : '';
        }
      ])
      ->add('updateServiceStatuses', CheckboxType::class, [
        'mapped' => false,
        'required' => false
      ])
      ->add('maintenanceServices', CollectionType::class, [
        'entry_type' => MaintenanceServiceType::class,
        'entry_options' => ['label' => false],
        'allow_add' => true,
        'allow_delete' => true,
        'by_reference' => false
      ])
      ->add('maintenanceUpdates', CollectionType::class, [
        'entry_type' => MaintenanceUpdateType::class,
        'entry_options' => ['label' => false],
        'allow_add' => true,
        'allow_delete' => true,
        'by_reference' => false
      ]);

    $builder->get('scheduledFor')
      ->addModelTransformer(new TimestampToDateTimeStringTransformer());

    $builder->get('anticipatedEnd')
      ->addModelTransformer(new TimestampToDateTimeStringTransformer());

    $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event)
    {
      $maintenance = $event->getData();
      $form = $event->getForm();

      if (!$maintenance  || $maintenance->getId() == null)
      {
        $form
        ->add('visibility', CheckboxType::class, [
          'required' => false,
          'attr' => [
            'checked' => true
          ]
        ]);
      }
    });
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'data_class' => Maintenance::class
    ]);
  }
}
