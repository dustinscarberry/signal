<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\User;

class UserApiController extends ApiController
{
  /**
   * @Route("/api/v1/users/{guid}", name="deleteUser", methods={"DELETE"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function deleteUser($guid)
  {
    //get user
    $user = $this->getDoctrine()
      ->getRepository(User::class)
      ->findByGuid($guid);

    //check for valid user
    if (!$user)
      return $this->respondWithErrors(['Invalid data']);

    //delete user
    $user->setDeletedOn(time());
    $user->setDeletedBy($this->getUser());
    $this->getDoctrine()->getManager()->flush();

    //respond with object
    return $this->respond($user);
  }
}
