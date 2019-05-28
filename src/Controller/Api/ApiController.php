<?php
namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
  /**
   * @var integer HTTP status code - 200 (OK) by default
   */
  protected $statusCode = 200;

  /**
   * Gets the value of statusCode.
   *
   * @return integer
   */
  public function getStatusCode()
  {
    return $this->statusCode;
  }

  /**
   * Sets the value of statusCode.
   *
   * @param integer $statusCode the status code
   *
   * @return self
   */
  protected function setStatusCode($statusCode)
  {
    $this->statusCode = $statusCode;
    return $this;
  }

  /**
   * Returns a JSON response
   *
   * @param array $data
   * @param array $headers
   *
   * @return Symfony\Component\HttpFoundation\JsonResponse
   */
  public function respond($data, $headers = [])
  {
    if ($data !== null)
    {
      $response = new \stdClass();
      $response->data = $data;
    }
    else
      $response = null;

    return new JsonResponse($response, $this->getStatusCode(), $headers);
  }

  /**
   * Sets an error message and returns a JSON response
   *
   * @param string $errors
   *
   * @return Symfony\Component\HttpFoundation\JsonResponse
   */
  public function respondWithErrors($errors, $headers = [])
  {
    $response = new \stdClass();
    $response->errors = $errors;

    return new JsonResponse($response, $this->getStatusCode(), $headers);
  }

  public function respondWithNull($headers = [])
  {
    return new JsonResponse(null, $this->getStatusCode(), $headers);
  }

  /**
   * Returns a 401 Unauthorized http response
   *
   * @param string $message
   *
   * @return Symfony\Component\HttpFoundation\JsonResponse
   */
  public function respondUnauthorized($message = 'Not authorized!')
  {
    return $this->setStatusCode(401)->respondWithErrors($message);
  }
}
