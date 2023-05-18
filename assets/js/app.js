import '../css/app.scss';
import { createRoot } from 'react-dom/client';

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
    createRoot(videoEmbed).render(<VideoEmbedWidget id={videoEmbed.getAttribute('data-id')}/>);
  });

if (servicesLists)
  Array.prototype.forEach.call(servicesLists, servicesList => {
    createRoot(servicesList).render(<ServicesListWidget id={servicesList.getAttribute('data-id')}/>);
  });

if (incidentsLists)
  Array.prototype.forEach.call(incidentsLists, incidentsList => {
    createRoot(incidentsList).render(<IncidentsListWidget id={incidentsList.getAttribute('data-id')}/>);
  });

if (maintenanceLists)
  Array.prototype.forEach.call(maintenanceLists, maintenanceList => {
    createRoot(maintenanceList).render(<MaintenanceListWidget id={maintenanceList.getAttribute('data-id')}/>);
  });

if (serviceStatusOverviews)
  Array.prototype.forEach.call(serviceStatusOverviews, serviceStatusOverview => {
    createRoot(serviceStatusOverview).render(<ServiceStatusOverviewWidget id={serviceStatusOverview.getAttribute('data-id')}/>);
  });

if (metricsOverviews)
  Array.prototype.forEach.call(metricsOverviews, metricsOverview => {
    createRoot(metricsOverview).render(<MetricsOverviewWidget id={metricsOverview.getAttribute('data-id')}/>);
  });

if (serviceUptimeCharts)
  Array.prototype.forEach.call(serviceUptimeCharts, serviceUptimeChart => {
    createRoot(serviceUptimeChart).render(<ServiceUptimeChartWidget id={serviceUptimeChart.getAttribute('data-id')}/>);
  });

if (customMetricCharts)
  Array.prototype.forEach.call(customMetricCharts, customMetricChart => {
    createRoot(customMetricChart).render(<CustomMetricChartWidget id={customMetricChart.getAttribute('data-id')}/>);
  });

if (pastFutureLinks)
  Array.prototype.forEach.call(pastFutureLinks, pastFutureLink => {
    createRoot(pastFutureLink).render(<PastFutureLinksWidget id={pastFutureLink.getAttribute('data-id')}/>);
  });