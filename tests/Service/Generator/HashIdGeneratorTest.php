<?php

namespace App\Tests\Service\Generator;

use App\Service\Generator\HashIdGenerator;
use PHPUnit\Framework\TestCase;

class HashIdGeneratorTest extends TestCase
{
  public function testGenerate()
  {
    // get result
    $result = HashIdGenerator::generate();

    // assert is string
    $this->assertIsString($result);
    // assert length of hashid
    $this->assertEquals(13, strlen($result));
  }
}
