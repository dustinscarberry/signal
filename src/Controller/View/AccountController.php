<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\UserType;
use App\Service\Factory\UserFactory;

class AccountController extends AbstractController
{
  #[Route('/dashboard/account', name: 'viewAccount')]
  public function view(Request $req, UserFactory $userFactory)
  {
    $user = $this->getUser();

    //create form object for user
    $form = $this->createForm(UserType::class, $user);

    //handle form request if posted
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid()) {
      $action = $req->request->get('regenerateApiToken')
        ? 'regenerateApiToken'
        : 'updateUser';

      if ($action == 'regenerateApiToken') {
        $user = $userFactory->regenerateApiToken($user);

        //refresh form with new api token included for user
        $form = $this->createForm(UserType::class, $user);

        $this->addFlash('success', 'API Token Regenerated');
      } else if ($action == 'updateUser') {
        //get new password field and update user
        $newPassword = $form->get('password')->getData();
        $userFactory->updateUser($user, $newPassword);

        $this->addFlash('success', 'Account updated');
      }
    }

    return $this->render('dashboard/account/view.html.twig', [
      'form' => $form->createView()
    ]);
  }
}