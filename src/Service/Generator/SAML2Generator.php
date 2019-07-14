<?php

namespace App\Service\Generator;

use LightSaml\Model\Protocol\AuthnRequest;
use LightSaml\Model\Context\SerializationContext;
use LightSaml\Model\Assertion\Issuer;
use LightSaml\SamlConstants;
use App\Model\SAML2Response;
use DateTime;

class SAML2Generator
{
  public static function getRequestUrl($destinationURL, $appIdentifer, $returnURL)
  {
    $id = self::generateId();
    $request = new AuthnRequest();
    $request
      ->setAssertionConsumerServiceURL($returnURL)
      ->setProtocolBinding(SamlConstants::BINDING_SAML2_HTTP_POST)
      ->setID($id)
      ->setIssueInstant(new DateTime())
      ->setDestination($destinationURL)
      ->setIssuer(new Issuer($appIdentifer)
    );

    $serializationContext = new SerializationContext();
    $request->serialize($serializationContext->getDocument(), $serializationContext);
    $request = $serializationContext->getDocument()->saveXML();
    $samlEncoded = urlencode(base64_encode(gzdeflate($request)));

    return $destinationURL . '?SAMLRequest=' . $samlEncoded;
  }

  public static function getValidatedUser($samlResponseData)
  {
    //create saml response object
    $samlResponse = new SAML2Response();
    $samlResponse->loadFromString($samlResponseData);
    $samlResponse->validate();

    //get subject of response
    return $samlResponse->getSubject();
  }

  private static function generateId()
  {
    return 'id_' . bin2hex(openssl_random_pseudo_bytes(24));
  }
}
