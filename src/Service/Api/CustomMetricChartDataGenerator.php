<?php

namespace App\Service\Api;

use Doctrine\ORM\EntityManagerInterface;
use App\Model\AppConfig;
use App\Entity\CustomMetricDatapoint;
use App\Entity\CustomMetric;
use \DateTime;

class CustomMetricChartDataGenerator
{
  private $em;

  public function __construct(EntityManagerInterface $em, AppConfig $appConfig)
  {
    date_default_timezone_set($appConfig->getSiteTimezone());
    $this->em = $em;
  }

  public function generate($scale, $metricId)
  {
    $metricId = $this->em
      ->getRepository(CustomMetric::class)
      ->findByHashId($metricId);

    if ($metricId)
      $metricId = $metricId->getId();

    $dataPoints = $this->getDataPoints($scale, $metricId);
    return [['id' => $this->getDatasetName($metricId), 'data' => $dataPoints]];
  }

  //get start datatime object
  private function getStartDateTime($scale)
  {
    $startDate = new DateTime();

    //back up correct amount of time
    if ($scale == 'day')
      $startDate->modify('-15 day');
    else if ($scale == 'hour')
      $startDate->modify('-12 hours');
    else if ($scale == 'minute')
      $startDate->modify('-15 minutes');

    return $startDate;
  }

  //increment datetime object based on scale
  private function incrementDateTime($datetime, string $scale)
  {
    if ($scale == 'day')
      $datetime->modify('1 day');
    else if ($scale == 'hour')
      $datetime->modify('1 hour');
    else if ($scale == 'minute')
      $datetime->modify('1 minute');
  }

  //get metric datapoints before specified timestamp
  private function getMetricDatapointsBefore($date, $metricId)
  {
    //get timestamp of startdate to filter db records with
    $timestamp = $date->getTimestamp();

    return $this->em
      ->getRepository(CustomMetricDatapoint::class)
      ->findAllBeforeTimestampWithMetric($timestamp, $metricId);
  }

  //get metric datapoints after specified timestamp
  private function getMetricDatapointsAfter($date, $metricId)
  {
    //get timestamp of startdate to filter db records with
    $timestamp = $date->getTimestamp();

    return $this->em
      ->getRepository(CustomMetricDatapoint::class)
      ->findAllAfterTimestampWithMetric($timestamp, $metricId);
  }

  //get dataset name, specific or generic
  private function getDatasetName(?string $metricId): string
  {
    $datasetName = 'Custom Metric';

    if ($metricId)
    {
      $metric = $this->em
        ->getRepository(CustomMetric::class)
        ->find($metricId);

      if ($metric)
        $datasetName = $metric->getName();
    }

    return $datasetName;
  }

  private function getDateTimeFormat($scale)
  {
    if ($scale == 'day')
      return 'Y-m-d';
    else if ($scale == 'hour')
      return 'Y-m-d H';
    else if ($scale == 'minute')
      return 'Y-m-d H:i';
    else
      return '';
  }

  private function getDataPointCount($scale)
  {
    if ($scale == 'day')
      return 15;
    else if ($scale == 'hour')
      return 12;
    else if ($scale == 'minute')
      return 15;
    else
      return 0;
  }

  private function getDataPoints($scale, $metricId)
  {
    $dataPoints = [];
    $startDate = $this->getStartDateTime($scale);
    $metricDatapointsAfter = $this->getMetricDatapointsAfter($startDate, $metricId);
    $dataPointCount = $this->getDataPointCount($scale);
    //track last computed datapoint
    $lastValue = null;

    for ($i = 0; $i <= $dataPointCount; $i++)
    {
      $records = [];
      $dateFormatString = $this->getDateTimeFormat($scale);
      $currentDateFormat = $startDate->format($dateFormatString);

      //track possible multiple metrics last values of each
      $lastValueArray = [];

      foreach ($metricDatapointsAfter as $datapoint)
      {
        $itemDateFormat = date($dateFormatString, $datapoint->getCreated());

        if ($currentDateFormat == $itemDateFormat)
        {
          $lastValue = $datapoint->getValue();
          $records[] = $lastValue;
        }
      }

      //compute average of multiple metrics if needed
      if ($lastValueArray)
        $lastValue = array_sum($lastValueArray) / count($lastValueArray);

      if (!empty($records))
        $dataValue = array_sum($records) / count($records);
      else {
        if ($i != 0)
        {
          if ($lastValue)
            $dataValue = $lastValue;
          else
            $dataValue = end($dataPoints)['y'];
        }
        else
          $dataValue = $this->getOffchartFirstDataPoint($startDate, $metricId, $scale);
      }

      $dataPoints[] = ['x' => $currentDateFormat, 'y' => $dataValue];
      $this->incrementDateTime($startDate, $scale);
    }

    return $dataPoints;
  }

  private function getOffchartFirstDataPoint($date, $metricId, $scale)
  {
    $dateFormatString = $this->getDateTimeFormat($scale);
    $metricDatapointsBefore = $this->getMetricDatapointsBefore($date, $metricId);
    $records = [];
    $firstPoint = null;

    foreach ($metricDatapointsBefore as $datapoint)
    {
      $itemDateFormat = date($dateFormatString, $datapoint->getCreated());

      if (!$firstPoint)
        $firstPoint = $itemDateFormat;
      else if ($firstPoint != $itemDateFormat)
        break;

      $records[] = $datapoint->getValue();
    }

    if (!empty($records))
      return array_sum($records) / count($records);
    else
      return 0;
  }
}
