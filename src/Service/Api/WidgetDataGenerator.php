<?php

namespace App\Service\Api;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Incident;
use App\Entity\Maintenance;
use App\Service\Api\ChartDataGenerator;
use App\Service\Api\CustomMetricChartDataGenerator;
use App\Service\Factory\ServiceFactory;
use App\Service\Factory\MaintenanceFactory;
use App\Service\Factory\IncidentFactory;
use App\Service\Factory\CustomMetricFactory;

class WidgetDataGenerator
{
  private $em;
  private $serviceStatusChartDataGenerator;
  private $customMetricChartDataGenerator;
  private $maintenanceFactory;
  private $incidentFactory;
  private $customMetricFactory;
  private $serviceFactory;
  private $data;

  public function __construct(
    EntityManagerInterface $em,
    ServiceStatusChartDataGenerator $serviceStatusChartDataGenerator,
    CustomMetricChartDataGenerator $customMetricChartDataGenerator,
    MaintenanceFactory $maintenanceFactory,
    IncidentFactory $incidentFactory,
    CustomMetricFactory $customMetricFactory,
    ServiceFactory $serviceFactory
  )
  {
    $this->em = $em;
    $this->serviceStatusChartDataGenerator = $serviceStatusChartDataGenerator;
    $this->customMetricChartDataGenerator = $customMetricChartDataGenerator;
    $this->maintenanceFactory = $maintenanceFactory;
    $this->incidentFactory = $incidentFactory;
    $this->customMetricFactory = $customMetricFactory;
    $this->serviceFactory = $serviceFactory;
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
    $services = $this->serviceFactory->getServices();

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
      $this->data['incidents'] = $this->incidentFactory->getPastIncidents(true, $options->maxItems);
    else
      $this->data['incidents'] = $this->incidentFactory->getIncidents(true, $options->maxItems);
  }

  private function getMaintenanceListData()
  {
    $options = $this->data['options']->getAttributes();

    if ($options->timeframe == 'scheduled')
      $this->data['maintenance'] = $this->maintenanceFactory->getScheduledMaintenances(false, $options->maxItems);
    else
      $this->data['maintenance'] = $this->maintenanceFactory->getMaintenances(false, $options->maxItems);
  }

  private function getServiceStatusOverviewData()
  {
    $services = $this->serviceFactory->getServices();

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
    $metric = $this->customMetricFactory->getCustomMetric(
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
