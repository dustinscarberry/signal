<?php

namespace App\Controller\View;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AboutController extends AbstractController
{
  /**
   * @Route("/dashboard/about", name="dashboardAbout")
   */
  public function about()
  {
    return $this->render('dashboard/about/view.html.twig');
  }
}
