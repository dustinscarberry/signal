<?php

namespace App\Service\Generator;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SSOLoginGenerator
{
  private $session;
  private $securityTokenStorage;
  private $eventDispatcher;

  public function __construct(
    SessionInterface $session,
    TokenStorageInterface $securityTokenStorage,
    EventDispatcherInterface $eventDispatcher
  )
  {
    $this->session = $session;
    $this->securityTokenStorage = $securityTokenStorage;
    $this->eventDispatcher = $eventDispatcher;
  }

  public function createUserSession(User $user, Request $req)
  {
    //main references firewall name being used
    $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
    $this->securityTokenStorage->setToken($token);

    //main in first parameter references firewall name
    $this->session->set('_security_main', serialize($token));

    //fire the login event manually
    $event = new InteractiveLoginEvent($req, $token);
    $this->eventDispatcher->dispatch("security.interactive_login", $event);
  }
}
