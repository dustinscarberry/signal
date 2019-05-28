<?php

namespace App\Service;

class ApiTokenGenerator
{
  public function __construct(){}

  public function generate()
  {
    return bin2hex(random_bytes(32));
  }
}
