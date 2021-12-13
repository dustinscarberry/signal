import '../css/app.scss';
import React from 'react';
import ReactDOM from 'react-dom';
import VideoEmbedWidget from './component/app/VideoEmbedWidget';
import ServicesListWidget from './component/app/ServicesListWidget';
import IncidentsListWidget from './component/app/IncidentsListWidget';
import MaintenanceListWidget from './component/app/MaintenanceListWidget';
import ServiceStatusOverviewWidget from './component/app/ServiceStatusOverviewWidget';
import MetricsOverviewWidget from './component/app/MetricsOverviewWidget';
import ServiceUptimeChartWidget from './component/app/ServiceUptimeChartWidget';
import CustomMetricChartWidget from './component/app/CustomMetricChartWidget';
import PastFutureLinksWidget from './component/app/PastFutureLinksWidget';

const videoEmbeds = document.querySelectorAll('.video-embed-widget-root');
const servicesLists = document.querySelectorAll('.services-list-widget-root');
const incidentsLists = document.querySelectorAll('.incidents-list-widget-root');
const maintenanceLists = document.querySelectorAll('.maintenance-list-widget-root');
const serviceStatusOverviews = document.querySelectorAll('.service-status-overview-widget-root');
const metricsOverviews = document.querySelectorAll('.metrics-overview-widget-root');
const serviceUptimeCharts = document.querySelectorAll('.service-uptime-chart-widget-root');
const customMetricCharts = document.querySelectorAll('.custom-metric-chart-widget-root');
const pastFutureLinks = document.querySelectorAll('.past-future-links-widget-root');

if (videoEmbeds)
  Array.prototype.forEach.call(videoEmbeds, videoEmbed => {
    ReactDOM.render(<VideoEmbedWidget id={videoEmbed.getAttribute('data-id')}/>, videoEmbed);
  });

if (servicesLists)
  Array.prototype.forEach.call(servicesLists, servicesList => {
    ReactDOM.render(<ServicesListWidget id={servicesList.getAttribute('data-id')}/>, servicesList);
  });

if (incidentsLists)
  Array.prototype.forEach.call(incidentsLists, incidentsList => {
    ReactDOM.render(<IncidentsListWidget id={incidentsList.getAttribute('data-id')}/>, incidentsList);
  });

if (maintenanceLists)
  Array.prototype.forEach.call(maintenanceLists, maintenanceList => {
    ReactDOM.render(<MaintenanceListWidget id={maintenanceList.getAttribute('data-id')}/>, maintenanceList);
  });

if (serviceStatusOverviews)
  Array.prototype.forEach.call(serviceStatusOverviews, serviceStatusOverview => {
    ReactDOM.render(<ServiceStatusOverviewWidget id={serviceStatusOverview.getAttribute('data-id')}/>, serviceStatusOverview);
  });

if (metricsOverviews)
  Array.prototype.forEach.call(metricsOverviews, metricsOverview => {
    ReactDOM.render(<MetricsOverviewWidget id={metricsOverview.getAttribute('data-id')}/>, metricsOverview);
  });

if (serviceUptimeCharts)
  Array.prototype.forEach.call(serviceUptimeCharts, serviceUptimeChart => {
    ReactDOM.render(<ServiceUptimeChartWidget id={serviceUptimeChart.getAttribute('data-id')}/>, serviceUptimeChart);
  });

if (customMetricCharts)
  Array.prototype.forEach.call(customMetricCharts, customMetricChart => {
    ReactDOM.render(<CustomMetricChartWidget id={customMetricChart.getAttribute('data-id')}/>, customMetricChart);
  });

if (pastFutureLinks)
  Array.prototype.forEach.call(pastFutureLinks, pastFutureLink => {
    ReactDOM.render(<PastFutureLinksWidget id={pastFutureLink.getAttribute('data-id')}/>, pastFutureLink);
  });
