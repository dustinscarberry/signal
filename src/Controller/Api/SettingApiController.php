<?php

namespace App\Controller\Api;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Model\AppConfig;
use App\Form\SettingType;

class SettingApiController extends ApiController
{
  /**
   * @Route("/api/v1/settings", name="updateSettings", methods={"PATCH"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function updateSettings(Request $request, AppConfig $appConfig)
  {
    $form = $this->createForm(SettingType::class, $appConfig);

    $data = json_decode($request->getContent(), true);
    $form->submit($data, false);

    if ($form->isSubmitted() && $form->isValid())
    {
      $appConfig = $form->getData();

      $logo = $appConfig->getLogo();

      if ($logo instanceof UploadedFile)
      {
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
