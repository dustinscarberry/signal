<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('username', TextType::class)
      ->add('firstName', TextType::class)
      ->add('lastName', TextType::class)
      ->add('email', TextType::class);

    $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event)
    {
      $user = $event->getData();
      $form = $event->getForm();

      if (!$user || $user->getId() == null)
      {
        $form
          ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Passwords do no match',
            'first_options' => ['label' => 'Password'],
            'second_options' => ['label' => 'Confirm Password']
          ]);
      }
      else
      {
        $form
          ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Passwords do no match',
            'first_options' => ['label' => 'New Password'],
            'second_options' => ['label' => 'Confirm New Password'],
            'mapped' => false,
            'required' => false
          ])
          ->add('apiToken', TextType::class, [
            'attr' => [
              'readonly' => true
            ]
          ])
          ->add('apiEnabled', CheckboxType::class, [
            'required' => false
          ]);
      }
    });
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'data_class' => User::class
    ]);
  }
}
