<?php

namespace App\Service\Manager;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Widget;

class WidgetManager
{
  private $em;

  public function __construct(
    EntityManagerInterface $em
  )
  {
    $this->em = $em;
  }

  public function createWidget($widget)
  {
    $this->em->persist($widget);
    $this->em->flush();
  }

  public function updateWidget()
  {
    $this->em->flush();
  }

  public function deleteWidget($widget)
  {
    $this->em->remove($widget);
    $this->em->flush();
  }

  public function getWidget($hashId)
  {
    return $this->em
      ->getRepository(Widget::class)
      ->findByHashId($hashId);
  }

  public function getWidgets()
  {
    return $this->em
      ->getRepository(Widget::class)
      ->findAllSorted();
  }
}
