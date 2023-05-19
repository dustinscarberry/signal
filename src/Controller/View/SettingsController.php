<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Model\AppConfig;
use App\Form\SettingType;

class SettingsController extends AbstractController
{
  #[Route('/dashboard/settings')]
  public function update(Request $req, AppConfig $appConfig)
  {
    $form = $this->createForm(SettingType::class, $appConfig);

    $previousLogo = $appConfig->getLogo();

    $form->handleRequest($req);

    if ($form->isSubmitted() && $form->isValid()) {
      $logo = $appConfig->getLogo();

      if ($logo) {
        $fileName = 'custom-logo.' . $logo->guessExtension();
        $appConfig->setLogo($fileName);

        try {
          $logo->move(
            $this->getParameter('base_directory'),
            $fileName
          );
        } catch(FileException $e) {
          $this->addFlash('error', $e->getMessage());
        }
      } else
        $appConfig->setLogo($previousLogo);

      //strip last slash off of siteUrl
      $appConfig->setSiteUrl(rtrim($appConfig->getSiteUrl(), '\\'));
      $appConfig->save();

      $this->addFlash('success', 'App settings updated');
    }

    return $this->render('dashboard/settings/viewall.html.twig', [
      'form' => $form->createView()
    ]);
  }
}