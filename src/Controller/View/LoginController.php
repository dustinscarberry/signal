<?php

namespace App\Controller\View;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
  /**
   * @Route("/dashboard/login", name="dashboardLogin")
   */
  public function login(AuthenticationUtils $authenticationUtils)
  {
    //get login error
    $error = $authenticationUtils->getLastAuthenticationError();
    //get last username entered by user
    $lastUsername = $authenticationUtils->getLastUsername();

    return $this->render('dashboard/login/login.html.twig', [
      'lastUsername' => $lastUsername,
      'error' => $error
    ]);
  }

  /**
   * @Route("/dashboard/logout", name="dashboardLogout")
   */
  public function logout()
  {
    //this is handled by configuration in security.yaml
  }
}
