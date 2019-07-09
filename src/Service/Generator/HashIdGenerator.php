<?php

namespace App\Service\Generator;

use Hashids\Hashids;

class HashIdGenerator
{
  public static function generate()
  {
    //// TODO: Possibly change salt to be randomly generated off of .env.local
    $hashids = new Hashids('213c526961', 12);
    return $hashids->encode(time() . mt_rand());
  }
}
