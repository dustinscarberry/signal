<?php

namespace App\Controller\View;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LogController extends AbstractController
{
  /**
   * @Route("/dashboard/logs", name="dashboardLogs")
   */
  public function logs()
  {
    $path = $this->getParameter('kernel.project_dir') . '/var/log/dev.log';
    $file = new \SplFileObject($path, 'r');
  	$file->seek(PHP_INT_MAX);
  	$last_line = $file->key();
  	$lines = new \LimitIterator($file, $last_line - 100, $last_line);
  	$lines = array_reverse(iterator_to_array($lines));

    //remove blank line at beginning
    if ($lines)
      array_shift($lines);

    return $this->render('dashboard/log/view.html.twig', [
      'logContentLines' => $lines
    ]);
  }
}
