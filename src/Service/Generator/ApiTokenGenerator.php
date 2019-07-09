<?php

namespace App\Service\Generator;

class ApiTokenGenerator
{
  public static function generate()
  {
    return bin2hex(random_bytes(32));
  }
}
