<?php

namespace App\Controller\View;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
  /**
   * @Route("/dashboard", name="dashboardHome")
   */
  public function home()
  {
    return $this->render('dashboard/home.html.twig');
  }
}
