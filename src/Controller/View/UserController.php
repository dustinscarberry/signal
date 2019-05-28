<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Form\UserType;
use App\Service\ApiTokenGenerator;

class UserController extends AbstractController
{
  /**
   * @Route("/dashboard/users", name="viewUsers")
   */
  public function viewall()
  {
    $users = $this->getDoctrine()
      ->getRepository(User::class)
      ->findAllNotDeleted();

    return $this->render('dashboard/user/viewall.html.twig', [
      'users' => $users
    ]);
  }

  /**
   * @Route("/dashboard/users/add")
   */
  public function add(Request $request, UserPasswordEncoderInterface $passwordEncoder)
  {
    //create user object
    $user = new User();

    //create form object for user
    $form = $this->createForm(UserType::class, $user);

    //handle form request if posted
    $form->handleRequest($request);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $user = $form->getData();

      //encode password and add roles
      $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
      $user->setRoles(['ROLE_ADMIN']);

      $em = $this->getDoctrine()->getManager();
      $em->persist($user);
      $em->flush();

      $this->addFlash('success', 'User added');
      return $this->redirectToRoute('viewUsers');
    }

    //render user add page
    return $this->render('dashboard/user/add.html.twig', [
      'userForm' => $form->createView()
    ]);
  }

  /**
   * @Route("/dashboard/users/{userGuid}", name="editUser")
   */
  public function edit(
    $userGuid,
    Request $request,
    UserPasswordEncoderInterface $passwordEncoder,
    ApiTokenGenerator $tokenGenerator
  )
  {
    //get user from database
    $user = $this->getDoctrine()
      ->getRepository(User::class)
      ->findByGuid($userGuid);

    //create form object for user
    $form = $this->createForm(UserType::class, $user);

    //handle form request if posted
    $form->handleRequest($request);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $action = $request->request->get('regenerateApiToken')
        ? 'regenerateApiToken'
        : 'updateUser';

      if ($action == 'regenerateApiToken')
      {
        $token = $tokenGenerator->generate();
        $user->setApiToken($token);

        $this->getDoctrine()->getManager()->flush();

        $form = $this->createForm(UserType::class, $user);

        $this->addFlash('success', 'API Token Regenerated');
      }
      else if ($action == 'updateUser')
      {
        $user = $form->getData();

        //get new password field
        $newPassword = $form->get('password')->getData();

        //change password if valid
        if ($newPassword)
          $user->setPassword($passwordEncoder->encodePassword($user, $newPassword));

        $this->getDoctrine()->getManager()->flush();

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
