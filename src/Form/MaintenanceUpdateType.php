<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\DataTransformer\TimestampToDateTimeStringTransformer;
use App\Entity\MaintenanceUpdate;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\Type\EditableTextType;

class MaintenanceUpdateType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('created', EditableTextType::class, [
        'required' => false,
        'label' => false
      ])
      ->add('message', TextareaType::class, [
        'label' => false
      ]);

    $builder->get('created')
      ->addModelTransformer(new TimestampToDateTimeStringTransformer());
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'data_class' => MaintenanceUpdate::class
    ]);
  }
}
