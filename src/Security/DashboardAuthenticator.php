<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Core\Security;

class DashboardAuthenticator extends AbstractFormLoginAuthenticator
{
  use TargetPathTrait;

  private $em;
  private $csrfTokenManager;
  private $passwordEncoder;
  private $router;
  private $isAPICall = false;

  public function __construct(
    EntityManagerInterface $em,
    CsrfTokenManagerInterface $csrfTokenManager,
    UserPasswordEncoderInterface $passwordEncoder,
    RouterInterface $router
  )
  {
    $this->em = $em;
    $this->csrfTokenManager = $csrfTokenManager;
    $this->passwordEncoder = $passwordEncoder;
    $this->router = $router;
  }

  public function supports(Request $request)
  {
    $this->isAPICall = $request->headers->has('X-API-TOKEN');

    return
      ($request->attributes->get('_route') === 'dashboardLogin'
      && $request->isMethod('POST'))
      || $this->isAPICall;
  }

  public function getCredentials(Request $request)
  {
    if ($this->isAPICall)
    {
      return [
        'apiToken' => $request->headers->get('X-API-TOKEN')
      ];
    }
    else
    {
      $credentials = [
        'username' => $request->request->get('username'),
        'password' => $request->request->get('password'),
        'csrf_token' => $request->request->get('_csrf_token')
      ];

      $request->getSession()->set(
        Security::LAST_USERNAME,
        $credentials['username']
      );

      return $credentials;
    }
  }

  public function getUser($credentials, UserProviderInterface $userProvider)
  {
    if ($this->isAPICall)
    {
      $apiToken = $credentials['apiToken'];

      // if a User object, checkCredentials() is called
      $user = $this->em->getRepository(User::class)
        ->findOneBy(['apiToken' => $apiToken]);

      //check for matched user
      if (!$user)
      {
        $data = ['message' => 'Invalid API Token'];
        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
      }
    }
    else
    {
      //check if crsf token if valid
      $token = new CsrfToken('authenticate', $credentials['csrf_token']);
      if (!$this->csrfTokenManager->isTokenValid($token))
        throw new InvalidCsrfTokenException();

      //get user from database
      $user = $this->em
        ->getRepository(User::class)
        ->findOneBy(['username' => $credentials['username']]);

      //check for matched user
      if (!$user)
        throw new CustomUserMessageAuthenticationException('Email could not be found.');
    }

    return $user;
  }

  public function checkCredentials($credentials, UserInterface $user)
  {
    //return valid if user is populated and api call
    if ($this->isAPICall && $user)
      return true;

    //return valid if username and password match and not api call
    return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
  }

  public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
  {
    //bypass success redirect if api call
    if ($this->isAPICall)
      return;

    if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey))
      return new RedirectResponse($targetPath);

    return new RedirectResponse($this->router->generate('dashboardHome'));
  }

  protected function getLoginUrl()
  {
    //return null if api call
    if ($this->isAPICall)
      return null;

    //return login page if normal login
    return $this->router->generate('dashboardLogin');
  }
}
