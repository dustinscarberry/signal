<?php

namespace App\Tests\Service\Generator;

use App\Service\Generator\ApiTokenGenerator;
use PHPUnit\Framework\TestCase;

class ApiTokenGeneratorTest extends TestCase
{
  public function testGenerate()
  {
    // get result
    $result = ApiTokenGenerator::generate();

    // assert is string token
    $this->assertIsString($result);
  }
}
