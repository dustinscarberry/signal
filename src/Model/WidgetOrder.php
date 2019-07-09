<?php

namespace App\Model;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Widget;

class WidgetOrder
{
  private $widgetIDs = [];

  private $em;

  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

  public function getWidgetIDs(): ?array
  {
    return $this->widgetIDs;
  }

  public function setWidgetIDs(array $widgetIDs): self
  {
    $this->widgetIDs = $widgetIDs;
    return $this;
  }

  public function save()
  {
    $respository = $this->em->getRepository(Widget::class);

    $widgetCount = count($this->widgetIDs);
    for ($i = 0; $i < $widgetCount; $i++)
    {
      $widget = $respository->findByHashId($this->widgetIDs[$i]);
      $widget->setSortorder($i);
    }

    $this->em->flush();
  }
}
