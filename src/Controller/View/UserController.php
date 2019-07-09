<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use App\Form\UserType;
use App\Service\Manager\UserManager;

class UserController extends AbstractController
{
  /**
   * @Route("/dashboard/users", name="viewUsers")
   */
  public function viewall(UserManager $userManager)
  {
    $users = $userManager->getUsers();

    return $this->render('dashboard/user/viewall.html.twig', [
      'users' => $users
    ]);
  }

  /**
   * @Route("/dashboard/users/add")
   */
  public function add(Request $req, UserManager $userManager)
  {
    //create user object
    $user = new User();

    //create form object for user
    $form = $this->createForm(UserType::class, $user);

    //handle form request if posted
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $userManager->createUser($user);

      $this->addFlash('success', 'User added');
      return $this->redirectToRoute('viewUsers');
    }

    //render user add page
    return $this->render('dashboard/user/add.html.twig', [
      'userForm' => $form->createView()
    ]);
  }

  /**
   * @Route("/dashboard/users/{hashId}", name="editUser")
   */
  public function edit($hashId, Request $req, UserManager $userManager)
  {
    //get user from database
    $user = $userManager->getUser($hashId);

    //create form object for user
    $form = $this->createForm(UserType::class, $user);

    //handle form request if posted
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $action = $req->request->get('regenerateApiToken')
        ? 'regenerateApiToken'
        : 'updateUser';

      if ($action == 'regenerateApiToken')
      {
        $user = $userManager->regenerateApiToken($user);

        //refresh form with new api token included for user
        $form = $this->createForm(UserType::class, $user);

        $this->addFlash('success', 'API Token Regenerated');
      }
      else if ($action == 'updateUser')
      {
        //get new password field and update user
        $newPassword = $form->get('password')->getData();
        $userManager->updateUser($user, $newPassword);

        $this->addFlash('success', 'User updated');
        return $this->redirectToRoute('viewUsers');
      }
    }

    //render service add page
    return $this->render('dashboard/user/edit.html.twig', [
      'userForm' => $form->createView()
    ]);
  }
}
