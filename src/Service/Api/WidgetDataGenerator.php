<?php

namespace App\Service\Api;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Incident;
use App\Entity\Maintenance;
use App\Service\Api\ChartDataGenerator;
use App\Service\Api\CustomMetricChartDataGenerator;
use App\Service\Manager\ServiceManager;
use App\Service\Manager\MaintenanceManager;
use App\Service\Manager\IncidentManager;
use App\Service\Manager\CustomMetricManager;

class WidgetDataGenerator
{
  private $em;
  private $serviceStatusChartDataGenerator;
  private $customMetricChartDataGenerator;
  private $maintenanceManager;
  private $incidentManager;
  private $customMetricManager;
  private $serviceManager;
  private $data;

  public function __construct(
    EntityManagerInterface $em,
    ServiceStatusChartDataGenerator $serviceStatusChartDataGenerator,
    CustomMetricChartDataGenerator $customMetricChartDataGenerator,
    MaintenanceManager $maintenanceManager,
    IncidentManager $incidentManager,
    CustomMetricManager $customMetricManager,
    ServiceManager $serviceManager
  )
  {
    $this->em = $em;
    $this->serviceStatusChartDataGenerator = $serviceStatusChartDataGenerator;
    $this->customMetricChartDataGenerator = $customMetricChartDataGenerator;
    $this->maintenanceManager = $maintenanceManager;
    $this->incidentManager = $incidentManager;
    $this->customMetricManager = $customMetricManager;
    $this->serviceManager = $serviceManager;
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
    else if ($widgetType == 'custom-metric-chart')
      $this->getCustomMetricChartData();

    return $this->data;
  }

  private function getServicesListData()
  {
    $services = $this->serviceManager->getServices();

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
    $options = $this->data['options']->getAttributes();

    if ($options->timeframe == 'past')
      $this->data['incidents'] = $this->incidentManager->getPastIncidents(true, $options->maxItems);
    else
      $this->data['incidents'] = $this->incidentManager->getIncidents(true, $options->maxItems);
  }

  private function getMaintenanceListData()
  {
    $options = $this->data['options']->getAttributes();

    if ($options->timeframe == 'scheduled')
      $this->data['maintenance'] = $this->maintenanceManager->getScheduledMaintenances(false, $options->maxItems);
    else
      $this->data['maintenance'] = $this->maintenanceManager->getMaintenances(false, $options->maxItems);
  }

  private function getServiceStatusOverviewData()
  {
    $services = $this->serviceManager->getServices();

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
      ->findAllScheduledMaintenance(false, false);

    $this->data['scheduledMaintenance'] = count($scheduledMaintenance);

    $lastIncident = $this->em
      ->getRepository(Incident::class)
      ->findLastIncident();

    $this->data['daysSinceLastIncident'] = 0;

    if ($lastIncident)
    {
      $lastIncidentDate = new \DateTime();
      $lastIncidentDate->setTimestamp($lastIncident->getOccurred());
      $currentDate = new \DateTime();

      $interval = $currentDate->diff($lastIncidentDate);
      $this->data['daysSinceLastIncident'] = $interval->days;
    }
  }

  private function getServiceUptimeChartData()
  {
    //get data for one or all services, with scale of day, hour, minute
    $this->data['dataPoints'] = $this->serviceStatusChartDataGenerator->generate(
      $this->data['options']->getAttributes()->scale,
      $this->data['options']->getAttributes()->service
    );
  }

  private function getCustomMetricChartData()
  {
    //get data for metric, with scale of day, hour, minute
    $this->data['dataPoints'] = $this->customMetricChartDataGenerator->generate(
      $this->data['options']->getAttributes()->scale,
      $this->data['options']->getAttributes()->metric
    );

    //get start and end scale of metric
    $metric = $this->customMetricManager->getCustomMetric(
      $this->data['options']->getAttributes()->metric
    );

    if ($metric)
    {
      $this->data['scaleStart'] = $metric->getScaleStart();
      $this->data['scaleEnd'] = $metric->getScaleEnd();
    }
    else
    {
      $this->data['scaleStart'] = 0;
      $this->data['scaleEnd'] = 100;
    }
  }
}
