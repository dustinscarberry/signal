<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class TimestampToDateTimeStringTransformer implements DataTransformerInterface
{
  public function transform($timestamp)
  {
    if (!$timestamp)
      return '';

    return date('m/d/Y g:i A', $timestamp);
  }

  public function reverseTransform($datetimeString)
  {
    if (is_numeric($datetimeString))
      return $datetimeString;

    return strtotime($datetimeString);
  }
}
