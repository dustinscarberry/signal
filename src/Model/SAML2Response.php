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
    //public key to verify signature
    $publicCert = new \LightSaml\Credential\X509Certificate();
    /*$publicCert->setData('MIIC8DCCAdigAwIBAgIQWS5zI5MYVrNDbvtp/xhY8jANBgkqhkiG9w0BAQsFADA0MTIwMAYDVQQD
EylNaWNyb3NvZnQgQXp1cmUgRmVkZXJhdGVkIFNTTyBDZXJ0aWZpY2F0ZTAeFw0xOTA3MTIxNjQ4
MDFaFw0yMjA3MTIxNjQ4MDFaMDQxMjAwBgNVBAMTKU1pY3Jvc29mdCBBenVyZSBGZWRlcmF0ZWQg
U1NPIENlcnRpZmljYXRlMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAxOe4eBJOKkly
ibrr6fpibLKD+Mue1kxxPnsnkxJbCSGLmWWeoElFzO8MkrTLpVVcxcZAHlFAsZAnhzusrDe/8THg
a/kZew8YaUiVYRxJJ2n2yYFLbMwAaNaOvT3h6SigA+9nOUajArZvHuHnZ3qqpx8p170ZoSL8Z5DA
0j3f3lKyll75tGi/x6Dq00f5mNBCV4G+2UJyaC2NrubQ/UvXGX3WUlEHFgNyo84tnzyXDkxa1Wre
wo44D9E7GYdGDbpwLWx5TODeF8hYtxiWFpGplzW3QZBFD0pnlvpvZNOZkACvSZtYKB1hFM0y8qVQ
jT/MilpKEThreFCi2RHWoEmmEwIDAQABMA0GCSqGSIb3DQEBCwUAA4IBAQCd7lH/bHnPh4Qq5vC2
/uguFgTlbUE0u/mYH+X/7gU0EiVAwlJTgwnC40EUli3b0Dxzo+gcxusUOEx0ljHmjfWian7uYxXd
xMoBjEsEP9m3C58SVj9+FPZJOURDldlo0DIm+TnYP1V76A2ozfrVvZ9ndDLFTQA1/fPwEUpXLyO3
QWe9qrfv9Nw9vMTnynmCxQ9rBItoWrlFFjY1VNAvW+orFGj/WbLixCwa1Js/L1bK0+F3coAfy1tQ
3Qo0qlMO47VrPJZTo6/IiiN1LMUBkzE1A0pAmvCk4cM8HUWJe5wtSphHnm1rOEUSlo9G0yPlQ8od
DQJdpidUV42J+tgCzSzs'
);*/

    $publicCert->setData($this->publicCert);
    $key = KeyHelper::createPublicKey($publicCert);

    //get signature from assertion
    $signatureReader = $this->assertion->getSignature();

    //validate signature - change this later to check all possible pubic keys against signature in response
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
