<?php

namespace App\Controller\View;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CustomMetricController extends AbstractController
{
  /**
   * @Route("/dashboard/custommetrics", name="dashboardCustomMetrics")
   */
  public function customMetrics()
  {
    return $this->render('dashboard/custommetrics/view.html.twig', [
      'metrics' => []
    ]);
  }
}
