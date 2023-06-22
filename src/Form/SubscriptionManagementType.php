<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ServiceCategory;
use App\Form\Type\SubscriptionGroupType;
use App\Model\SubscriptionManagement;

class SubscriptionManagementType extends AbstractType
{
  private $em;

  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $serviceCategories = $this->em
      ->getRepository(ServiceCategory::class)
      ->findAllNotDeleted();

    foreach ($serviceCategories as $serviceCategory) {
      //remove empty service categories
      if (count($serviceCategory->getServices()) == 0)
        continue;

      $builder->add('serviceCategory-' . $serviceCategory->getId(), SubscriptionGroupType::class, [
        'groupName' => $serviceCategory->getName(),
        'groupItems' => $serviceCategory->getServices(),
        'label' => false
      ]);
    }
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => SubscriptionManagement::class
    ]);
  }
}
