<?php

namespace App\Service\Generator;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SSOLoginGenerator
{
  private $requestStack;
  private $securityTokenStorage;
  private $eventDispatcher;

  public function __construct(
    RequestStack $requestStack,
    TokenStorageInterface $securityTokenStorage,
    EventDispatcherInterface $eventDispatcher
  )
  {
    $this->requestStack = $requestStack;
    $this->securityTokenStorage = $securityTokenStorage;
    $this->eventDispatcher = $eventDispatcher;
  }

  public function createUserSession(User $user, Request $req)
  {
    //main references firewall name being used
    $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
    $this->securityTokenStorage->setToken($token);

    //main in first parameter references firewall name
    $session = $this->requestStack->getSession();
    $session->set('_security_main', serialize($token));

    //fire the login event manually
    $event = new InteractiveLoginEvent($req, $token);
    $this->eventDispatcher->dispatch("security.interactive_login", $event);
  }
}
