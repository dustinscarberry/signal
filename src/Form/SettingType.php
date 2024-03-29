<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Model\AppConfig;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class SettingType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('siteName', TextType::class)
      ->add('siteUrl', TextType::class)
      ->add('siteAbout', TextareaType::class, [
        'required' => false
      ])
      ->add('allowSubscriptions', CheckboxType::class, [
        'required' => false
      ])
      ->add('siteTimezone', TimezoneType::class)
      ->add('dashboardTheme', ChoiceType::class, [
        'choices' => [
          'Purple' => 'purple',
          'Blue' => 'blue',
          'White' => 'white'
        ]
      ])
      ->add('appTheme', ChoiceType::class, [
        'choices' => [
          'Dark' => 'dark',
          'Medium Dark' => 'mediumdark',
          'Light' => 'light'
        ]
      ])
      ->add('cssOverrides', TextareaType::class, [
        'required' => false
      ])
      ->add('analyticsGoogleID', TextType::class, [
        'required' => false,
        'label' => 'Google Analytics ID'
      ])
      ->add('locale', LocaleType::class)
      ->add('language', LanguageType::class)
      ->add('mailFromAddress', TextType::class)
      ->add('mailFromName', TextType::class)
      ->add('enableSaml2Login', CheckboxType::class, [
        'required' => false
      ])
      ->add('saml2AppIdentifier', TextType::class, [
        'required' => false
      ])
      ->add('saml2IdpLoginUrl', TextType::class, [
        'required' => false
      ])
      ->add('saml2IdpSigningCertificate', TextareaType::class, [
        'required' => false
      ])
      ->add('saml2SubjectIdentifier', TextType::class, [
        'required' => false
      ])
      ->add('logo', FileType::class, [
        'required' => false,
        'attr' => ['accept' => '.jpg,.jpeg,.png'],
        'data_class' => null
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => AppConfig::class,
      'csrf_protection' => false
    ]);
  }
}
