<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Subscription;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SubscriptionType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder->add('email', TextType::class, [
      'label' => false,
      'attr' => [
        'placeholder' => 'email@example.com'
      ]
    ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Subscription::class
    ]);
  }
}
