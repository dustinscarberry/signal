<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use App\Form\DataTransformer\TimestampToDateTimeStringTransformer;
use App\Entity\Incident;
use App\Entity\IncidentStatus;
use App\Entity\IncidentType as IncidentEntityType;
use App\Form\IncidentServiceType;
use App\Form\IncidentUpdateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class IncidentType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('name', TextType::class)
      ->add('message', TextareaType::class)
      ->add('visibility', CheckboxType::class, [
        'required' => false
      ])
      ->add('occurred', TextType::class)
      ->add('anticipatedResolution', TextType::class, [
        'required' => false
      ])
      ->add('status', EntityType::class, [
        'class' => IncidentStatus::class,
        'choice_label' => 'name'
      ])
      ->add('type', EntityType::class, [
        'class' => IncidentEntityType::class,
        'choice_label' => 'name'
      ])
      ->add('incidentServices', CollectionType::class, [
        'entry_type' => IncidentServiceType::class,
        'entry_options' => ['label' => false],
        'allow_add' => true,
        'allow_delete' => true,
        'by_reference' => false
      ])
      ->add('incidentUpdates', CollectionType::class, [
        'entry_type' => IncidentUpdateType::class,
        'entry_options' => ['label' => false],
        'allow_add' => true,
        'allow_delete' => true,
        'by_reference' => false
      ]);

      $builder->get('occurred')
        ->addModelTransformer(new TimestampToDateTimeStringTransformer());

      $builder->get('anticipatedResolution')
        ->addModelTransformer(new TimestampToDateTimeStringTransformer());

      $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event)
      {
        $incident = $event->getData();
        $form = $event->getForm();

        if (!$incident || $incident->getId() == null)
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
      'data_class' => Incident::class
    ]);
  }
}
