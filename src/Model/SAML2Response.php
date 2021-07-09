<?php

namespace App\Model;

use LightSaml\Model\Context\DeserializationContext;
use LightSaml\Model\Protocol\Response;
use LightSaml\Credential\KeyHelper;
use Exception;

class SAML2Response
{
  private $response;
  private $assertion;
  private $publicCert;

  public function loadFromString($responseString, $publicCert)
  {
    //set public cert
    $this->publicCert = $publicCert;

    //parse response to object
    $deserializationContext = new DeserializationContext();
    $deserializationContext->getDocument()->loadXML($responseString);
    $this->response = new Response();
    $this->response->deserialize($deserializationContext->getDocument()->firstChild, $deserializationContext);

    //get assertion
    $this->assertion = $this->response->getFirstAssertion();
  }

  public function validate()
  {
    $this->validateSignature();
    $this->validateConditions();
    $this->validateKnownPublicSigningCert();
  }

  public function getSubject()
  {
    return $this->assertion
      ->getSubject()
      ->getNameID()
      ->getValue();
  }

  public function getSubjectFormat()
  {
    return $this->assertion
      ->getSubject()
      ->getNameID()
      ->getFormat();
  }

  public function getSessionId()
  {
    return $this->response->getInResponseTo();
  }

  private function validateSignature()
  {
    // public key to verify signature
    $publicCert = new \LightSaml\Credential\X509Certificate();

    $publicCert->setData($this->publicCert);
    $key = KeyHelper::createPublicKey($publicCert);

    // get signature from assertion
    $signatureReader = $this->assertion->getSignature();

    // validate signature - change this later to check all possible pubic keys against signature in response
    if (!$signatureReader->validate($key))
      throw new Exception('SAML Response Error: Signature Invalid');
  }

  private function validateConditions()
  {
    //check time conditions
    $conditions = $this->assertion->getConditions();
    $notBefore = $conditions->getNotBeforeTimestamp();
    $notOnOrAfter = $conditions->getNotOnOrAfterTimestamp();

    if (time() < $notBefore || time() >= $notOnOrAfter)
      throw new Exception('SAML Response Error: Timestamp invalid');
  }

  private function validateKnownPublicSigningCert()
  {
    //// TODO: check for known loaded cert in idp to confirm correct host and avoid man-in-the-middle
  }
}
