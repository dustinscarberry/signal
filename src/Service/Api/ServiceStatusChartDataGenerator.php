<?php

namespace App\Service\Api;

use Doctrine\ORM\EntityManagerInterface;
use App\Model\AppConfig;
use App\Entity\ServiceStatusHistory;
use App\Entity\Service;
use \DateTime;

class ServiceStatusChartDataGenerator
{
  private $em;

  public function __construct(EntityManagerInterface $em, AppConfig $appConfig)
  {
    date_default_timezone_set($appConfig->getSiteTimezone());
    $this->em = $em;
  }

  public function generate($scale, $serviceId)
  {
    if ($serviceId == 'all')
      $serviceId = null;
    else
    {
      $serviceId = $this->em
        ->getRepository(Service::class)
        ->findByHashId($serviceId);

      if ($serviceId)
        $serviceId = $serviceId->getId();
    }

    $dataPoints = $this->getDataPoints($scale, $serviceId);
    return [['id' => $this->getDatasetName($serviceId), 'data' => $dataPoints]];
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

  //get service histories before specified timestamp
  private function getServiceHistoriesBefore($date, $serviceId)
  {
    //get timestamp of startdate to filter db records with
    $timestamp = $date->getTimestamp();

    if ($serviceId)
      return $this->em
        ->getRepository(ServiceStatusHistory::class)
        ->findAllBeforeTimestampWithService($timestamp, $serviceId);
    else
    {
      $unfilteredStatuses = $this->em
        ->getRepository(ServiceStatusHistory::class)
        ->findAllBeforeTimestamp($timestamp);

      //temporary data variables to filter with
      $filteredData = [];
      $usedNeedles = [];

      foreach ($unfilteredStatuses as $item)
      {
        $id = $item->getService()->getId();

        if (!in_array($id, $usedNeedles))
        {
          $usedNeedles[] = $id;
          $filteredData[] = $item;
        }
      }

      return $filteredData;
    }
  }

  //get service histories after specified timestamp
  private function getServiceHistoriesAfter($date, $serviceId)
  {
    //get timestamp of startdate to filter db records with
    $timestamp = $date->getTimestamp();

    //if service specified
    if ($serviceId)
      return $this->em
        ->getRepository(ServiceStatusHistory::class)
        ->findAllAfterTimestampWithService($timestamp, $serviceId);
    else
      return $this->em
        ->getRepository(ServiceStatusHistory::class)
        ->findAllAfterTimestamp($timestamp);
  }

  //get dataset name, specific or generic
  private function getDatasetName(?string $serviceId): string
  {
    $datasetName = 'All Services';

    if ($serviceId)
    {
      $service = $this->em
        ->getRepository(Service::class)
        ->find($serviceId);

      if ($service)
        $datasetName = $service->getName();
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

  private function getDataPoints($scale, $serviceId)
  {
    $dataPoints = [];
    $startDate = $this->getStartDateTime($scale);
    $serviceStatusHistoriesAfter = $this->getServiceHistoriesAfter($startDate, $serviceId);
    $dataPointCount = $this->getDataPointCount($scale);
    //track last computed datapoint
    $lastValue = null;

    for ($i = 0; $i <= $dataPointCount; $i++)
    {
      $records = [];
      $dateFormatString = $this->getDateTimeFormat($scale);
      $currentDateFormat = $startDate->format($dateFormatString);

      //track possible multiple services last values of each
      $lastValueArray = [];

      foreach ($serviceStatusHistoriesAfter as $serviceStatusHistory)
      {
        $itemDateFormat = date($dateFormatString, $serviceStatusHistory->getCreated());

        if ($currentDateFormat == $itemDateFormat)
        {
          $records[] = $serviceStatusHistory->getStatus()->getMetricValue();

          if ($serviceId)
            $lastValue = $serviceStatusHistory->getStatus()->getMetricValue();
          else
          {
            $key = $serviceStatusHistory->getId();
            $value = $serviceStatusHistory->getStatus()->getMetricValue();
            $lastValueArray[$key] = $value;
          }
        }
      }

      //compute average of multiple services if needed
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
          $dataValue = $this->getOffchartFirstDataPoint($startDate, $serviceId, $scale);
      }

      $dataPoints[] = ['x' => $currentDateFormat, 'y' => $dataValue];
      $this->incrementDateTime($startDate, $scale);
    }

    return $dataPoints;
  }

  private function getOffchartFirstDataPoint($date, $serviceId, $scale)
  {
    $dateFormatString = $this->getDateTimeFormat($scale);
    $serviceStatusHistoriesBefore = $this->getServiceHistoriesBefore($date, $serviceId);
    $records = [];
    $firstPoint = null;

    foreach ($serviceStatusHistoriesBefore as $serviceStatusHistory)
    {
      $itemDateFormat = date($dateFormatString, $serviceStatusHistory->getCreated());

      if (!$firstPoint)
        $firstPoint = $itemDateFormat;
      else if ($firstPoint != $itemDateFormat)
        break;

      $records[] = $serviceStatusHistory->getStatus()->getMetricValue();
    }

    if (!empty($records))
      return array_sum($records) / count($records);
    else
      return 0;
  }
}
