<?php

namespace App\Controller\View;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FAQController extends AbstractController
{
  #[Route('/dashboard/faq', name: 'dashboardFAQ')]
  public function faq()
  {
    return $this->render('dashboard/faq/view.html.twig');
  }
}
