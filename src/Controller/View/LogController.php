<?php

namespace App\Controller\View;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\Manager\LogManager;

class LogController extends AbstractController
{
  /**
   * @Route("/dashboard/logs", name="dashboardLogs")
   */
  public function logs()
  {
    $path = $this->getParameter('kernel.logs_dir') . '/'
      . $this->getParameter('kernel.environment') . '.log';

    $logData = LogManager::parseLog($path);

    return $this->render('dashboard/log/view.html.twig', [
      'logData' => $logData
    ]);
  }
}
