<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\UserType;

class AccountController extends AbstractController
{
  /**
   * @Route("/dashboard/account", name="viewAccount")
   */
  public function view(Request $req)
  {
    $user = $this->getUser();

    //create form object for user
    $form = $this->createForm(UserType::class, $user);

    //handle form request if posted
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $this->getDoctrine()->getManager()->flush();
    }

    //render acccount page
    return $this->render('dashboard/account/view.html.twig', [
      'form' => $form->createView()
    ]);
  }
}
