<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class SubscriptionGroupType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    foreach ($options['groupItems'] as $index => $item)
    {
      $builder->add('service-' . $item->getId(), CheckboxType::class, [
        'label' => $item->getName(),
        'required' => false
      ]);
    }
  }

  public function buildView(FormView $view, FormInterface $form, array $options): void
  {
    parent::buildView($view, $form, $options);

    $view->vars = array_merge($view->vars, array(
      'groupName' => $options['groupName']
    ));
  }

  public function getParent(): ?string
  {
    return FormType::class;
  }

  public function getName()
  {
    return 'subscription_group';
  }

  public function getBlockPrefix(): string
  {
    return 'subscription_group';
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'groupName' => '',
      'groupItems' => []
    ]);
  }
}
