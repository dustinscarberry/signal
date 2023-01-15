<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use App\Model\AppConfig;

class TimestampToDateTimeStringTransformer implements DataTransformerInterface
{
  private $timezone;

  public function __construct(string $timezone)
  {
    $this->timezone = $timezone;
  }

  public function transform($timestamp): mixed
  {
    if (!$timestamp)
      return '';

    date_default_timezone_set($this->timezone);

    return date('m/d/Y g:i A', $timestamp);
  }

  public function reverseTransform($datetimeString): mixed
  {
    if (is_numeric($datetimeString))
      return $datetimeString;

    date_default_timezone_set($this->timezone);

    return strtotime($datetimeString);
  }
}
