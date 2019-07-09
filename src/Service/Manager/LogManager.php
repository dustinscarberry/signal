<?php

namespace App\Service\Manager;

class LogManager
{
  public static function parseLog($path, $maxLines = 100, $reverseLog = true)
  {
    //load log lines
    $file = new \SplFileObject($path, 'r');
    $file->seek(PHP_INT_MAX);
    $last_line = $file->key();
    $lines = new \LimitIterator($file, $last_line - $maxLines, $last_line);

    //reverse logs
    if ($reverseLog)
      $lines = array_reverse(iterator_to_array($lines));

    //parse lines into log data
    $logData = [];
    foreach ($lines as $line)
    {
      if ($line == '')
        continue;

      $data = new \stdClass();
      $time = strtok($line, ']') . ']';
      $line = str_replace($time, '', $line);
      $errorType = strtok($line, ':') . ':';
      $message = trim(str_replace($errorType, '', $line));
      $errorType = trim(rtrim($errorType, ':'));

      if (strpos($errorType, 'DEBUG') !== false)
        $data->type = 'debug';
      else if (strpos($errorType, 'CRITICAL') != false)
        $data->type = 'critical';
      else if (strpos($errorType, 'INFO') != false)
        $data->type = 'info';
      else if (strpos($errorType, 'NOTICE') != false)
        $data->type = 'notice';
      else if (strpos($errorType, 'WARNING') != false)
        $data->type = 'warning';
      else if (strpos($errorType, 'ERROR') != false)
        $data->type = 'error';
      else if (strpos($errorType, 'ALERT') != false)
        $data->type = 'alert';
      else if (strpos($errorType, 'EMERGENCY') != false)
        $data->type = 'emergency';

      $data->time = $time;
      $data->errorType = $errorType;
      $data->message = $message;
      $logData[] = $data;
    }

    return $logData;
  }
}
