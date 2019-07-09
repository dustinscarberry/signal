<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Service\Manager\UserManager;

class UserApiController extends ApiController
{
  /**
   * @Route("/api/v1/users/{hashId}", name="deleteUser", methods={"DELETE"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function deleteUser($hashId, UserManager $userManager)
  {
    //get user
    $user = $userManager->getUser($hashId);

    //check for valid user
    if (!$user)
      return $this->respondWithErrors(['Invalid data']);

    //delete user
    $userManager->deleteUser($user);

    //respond with object
    return $this->respond($user);
  }
}
