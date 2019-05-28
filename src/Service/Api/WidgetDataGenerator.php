<?php

namespace App\Service\Api;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Service;
use App\Entity\Incident;
use App\Entity\Maintenance;
use App\Entity\ServiceStatusHistory;
use App\Service\Api\ChartDataGenerator;
use \DateTime;

class WidgetDataGenerator
{
  private $em;
  private $chartDataGenerator;
  private $data;

  public function __construct(EntityManagerInterface $em, ChartDataGenerator $chartDataGenerator)
  {
    $this->em = $em;
    $this->chartDataGenerator = $chartDataGenerator;
  }

  //returns data for widget passed in
  public function getData($widget)
  {
    $this->data = [];
    $this->data['options'] = $widget;
    $widgetType = $widget->getType();

    if ($widgetType == 'services-list')
      $this->getServicesListData();
    else if ($widgetType == 'incidents-list')
      $this->getIncidentsListData();
    else if ($widgetType == 'maintenance-list')
      $this->getMaintenanceListData();
    else if ($widgetType == 'service-status-overview')
      $this->getServiceStatusOverviewData();
    else if ($widgetType == 'metrics-overview')
      $this->getMetricsOverviewData();
    else if ($widgetType == 'service-uptime-chart')
      $this->getServiceUptimeChartData();

    return $this->data;
  }

  private function getServicesListData()
  {
    $services = $this->em
      ->getRepository(Service::class)
      ->findAllNotDeleted();

    $this->data['services'] = [];

    foreach ($services as $service)
    {
      $serviceCategory = $service->getServiceCategory()->getName();

      if (!array_key_exists($serviceCategory, $this->data['services']))
        $this->data['services'][$serviceCategory] = [];

      $this->data['services'][$serviceCategory][] = $service;
    }
  }

  private function getIncidentsListData()
  {
    $incidents = $this->em
      ->getRepository(Incident::class)
      ->findAllNotDeleted();

    $this->data['incidents'] = $incidents;
  }

  private function getMaintenanceListData()
  {
    $maintenance = $this->em
      ->getRepository(Maintenance::class)
      ->findAllNotDeleted();

    $this->data['maintenance'] = $maintenance;
  }

  private function getServiceStatusOverviewData()
  {
    $services = $this->em
      ->getRepository(Service::class)
      ->findAllNotDeleted();

    $this->data['serviceStatuses'] = array_map(function($item){
      return $item->getStatus()->getType();
    }, $services);
  }

  private function getMetricsOverviewData()
  {
    $activeIncidents = $this->em
      ->getRepository(Incident::class)
      ->findAllActiveIncidents();

    $this->data['activeIncidents'] = count($activeIncidents);

    $scheduledMaintenance = $this->em
      ->getRepository(Maintenance::class)
      ->findAllScheduledMaintenance();

    $this->data['scheduledMaintenance'] = count($scheduledMaintenance);

    $lastIncident = $this->em
      ->getRepository(Incident::class)
      ->findLastIncident();

    $this->data['daysSinceLastIncident'] = 0;

    if ($lastIncident)
    {
      $lastIncidentDate = new DateTime();
      $lastIncidentDate->setTimestamp($lastIncident->getOccurred());
      $currentDate = new DateTime();

      $interval = $currentDate->diff($lastIncidentDate);
      $this->data['daysSinceLastIncident'] = $interval->days;
    }
  }

  private function getServiceUptimeChartData()
  {
    //get data for one or all services, with scale of day, hour, minute
    $this->data['dataPoints'] = $this->chartDataGenerator->generate(
      $this->data['options']->getAttributes()->scale,
      $this->data['options']->getAttributes()->service
    );
  }
}
