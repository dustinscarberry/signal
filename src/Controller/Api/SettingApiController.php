<?php

namespace App\Controller\Api;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\ExpressionLanguage\Expression;
use App\Model\AppConfig;
use App\Form\SettingType;

class SettingApiController extends ApiController
{
  #[Route('/api/v1/settings', name: 'updateSettings', methods: ['PATCH'])]
  #[IsGranted(new Expression("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')"))]
  public function updateSettings(Request $req, AppConfig $appConfig)
  {
    $form = $this->createForm(SettingType::class, $appConfig);

    $data = json_decode($req->getContent(), true);
    $form->submit($data, false);

    if ($form->isSubmitted() && $form->isValid()) {
      $logo = $appConfig->getLogo();

      if ($logo instanceof UploadedFile) {
        $fileName = 'custom-logo.' . $logo->guessExtension();
        $appConfig->setLogo($fileName);

        try {
          $logo->move(
            $this->getParameter('base_directory'),
            $fileName
          );
        } catch(FileException $e) {
          return $this->respondWithErrors($e->getMessage());
        }
      }

      $appConfig->save();

      return $this->respond($appConfig);
    }

    return $this->respondWithErrors(['Invalid data']);
  }
}
