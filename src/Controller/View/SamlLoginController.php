<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\Generator\SAML2Generator;
use App\Service\Generator\SSOLoginGenerator;
use App\Model\AppConfig;
use App\Service\Factory\UserFactory;
use Symfony\Form\Exception\InvalidPropertyException;

class SamlLoginController extends AbstractController
{
  #[Route('/dashboard/samllogin', name: 'dashboardSamlLogin')]
  public function login(AppConfig $appConfig)
  {
    //check if user already logged in
    if ($this->getUser())
      return $this->redirectToRoute('dashboardHome');

    //get response url
    $responseUrl = $appConfig->getSiteUrl();
    $responseUrl = rtrim($responseUrl, '/') . '/dashboard/samlvalidate';

    //get redirect url
    $redirectURL = SAML2Generator::getRequestUrl(
      $appConfig->getSaml2IdpLoginUrl(),
      $appConfig->getSaml2AppIdentifier(),
      $responseUrl
    );

    return $this->redirect($redirectURL);
  }

  #[Route('/dashboard/samlvalidate', name: 'dashboardSamlValidate')]
  public function samlValidate(
    Request $req,
    UserFactory $userFactory,
    SSOLoginGenerator $ssoLoginGenerator,
    AppConfig $appConfig
  ) {
    $samlResponseData = base64_decode($req->request->get('SAMLResponse'));

    if (!$samlResponseData)
      throw new InvalidPropertyException('No valid IDP SAML Response');

    //get authenticated user
    $authenticatedUsername = SAML2Generator::getValidatedUser(
      $samlResponseData,
      $appConfig->getSaml2IdpSigningCertificate()
    );
    $authenticatedUser = $userFactory->getUserByUsername($authenticatedUsername);

    if (!$authenticatedUser)
      $this->redirectToRoute('dashboardLogin');

    //log authenticated user in manually
    $ssoLoginGenerator->createUserSession($authenticatedUser, $req);

    //redirect user to dashboard home
    return $this->redirectToRoute('dashboardHome');
  }
}
