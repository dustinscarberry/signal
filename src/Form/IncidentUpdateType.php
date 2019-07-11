<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\DataTransformer\TimestampToDateTimeStringTransformer;
use App\Entity\IncidentUpdate;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\Type\EditableTextType;
use App\Model\AppConfig;

class IncidentUpdateType extends AbstractType
{
  private $siteTimezone;

  public function __construct(AppConfig $appConfig)
  {
    $this->siteTimezone = $appConfig->getSiteTimezone();
  }

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
      ->addModelTransformer(new TimestampToDateTimeStringTransformer($this->siteTimezone));
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'data_class' => IncidentUpdate::class
    ]);
  }
}
