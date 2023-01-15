<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\ExpressionLanguage\Expression;
use App\Service\Factory\UserFactory;

class UserApiController extends ApiController
{
  #[Route('/api/v1/users/{hashId}', name: 'deleteUser', methods: ['DELETE'])]
  #[IsGranted(new Expression("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')"))]
  public function deleteUser($hashId, UserFactory $userFactory)
  {
    //get user
    $user = $userFactory->getUser($hashId);

    //check for valid user
    if (!$user)
      return $this->respondWithErrors(['Invalid data']);

    //delete user
    $userFactory->deleteUser($user);

    //respond with object
    return $this->respond($user);
  }
}
