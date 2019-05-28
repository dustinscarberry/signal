export const WIDGET_BLOCK_TYPE = {
  VIDEO_EMBED: 'video-embed',
  SERVICES_LIST: 'services-list',
  INCIDENTS_LIST: 'incidents-list',
  MAINTENANCE_LIST: 'maintenance-list',
  SERVICE_STATUS_OVERVIEW: 'service-status-overview',
  METRICS_OVERVIEW: 'metrics-overview',
  SERVICE_UPTIME_CHART: 'service-uptime-chart'
}

export const WIDGET_BLOCK_ATTRIBUTES = {
  [WIDGET_BLOCK_TYPE.VIDEO_EMBED]: {
    title: 'Video Embed',
    iconClass: 'fas fa-video'
  },
  [WIDGET_BLOCK_TYPE.SERVICES_LIST]: {
    title: 'Services List',
    iconClass: 'fas fa-th'
  },
  [WIDGET_BLOCK_TYPE.INCIDENTS_LIST]: {
    title: 'Incidents List',
    iconClass: 'fas fa-exclamation-triangle'
  },
  [WIDGET_BLOCK_TYPE.MAINTENANCE_LIST]: {
    title: 'Maintenance List',
    iconClass: 'fas fa-wrench'
  },
  [WIDGET_BLOCK_TYPE.SERVICE_STATUS_OVERVIEW]: {
    title: 'Status Overview',
    iconClass: 'fas fa-mountain'
  },
  [WIDGET_BLOCK_TYPE.METRICS_OVERVIEW]: {
    title: 'Metrics Overview',
    iconClass: 'fas fa-stream'
  },
  [WIDGET_BLOCK_TYPE.SERVICE_UPTIME_CHART]: {
    title: 'Service Uptime Chart',
    iconClass: 'fas fa-hourglass-start'
  }
}

export const WIDGET_BLOCK_DATA = {
  [WIDGET_BLOCK_TYPE.VIDEO_EMBED]: {
    id: undefined,
    type: 'video-embed',
    sortorder: undefined,
    attributes: {
      source: undefined,
      sourceID: undefined,
      url: ''
    }
  },
  [WIDGET_BLOCK_TYPE.SERVICES_LIST]: {
    id: undefined,
    type: 'services-list',
    sortOrder: undefined,
    attributes: {
      layout: undefined,
      useGroups: false,
      refreshInterval: 120
    }
  },
  [WIDGET_BLOCK_TYPE.INCIDENTS_LIST]: {
    id: undefined,
    type: 'incidents-list',
    sortOrder: undefined,
    attributes: {
      title: undefined,
      refreshInterval: 120
    }
  },
  [WIDGET_BLOCK_TYPE.MAINTENANCE_LIST]: {
    id: undefined,
    type: 'maintenance-list',
    sortOrder: undefined,
    attributes: {
      title: undefined,
      refreshInterval: 120
    }
  },
  [WIDGET_BLOCK_TYPE.SERVICE_STATUS_OVERVIEW]: {
    id: undefined,
    type: 'service-status-overview',
    sortOrder: undefined,
    attributes: {
      refreshInterval: 120
    }
  },
  [WIDGET_BLOCK_TYPE.METRICS_OVERVIEW]: {
    id: undefined,
    type: WIDGET_BLOCK_TYPE.METRICS_OVERVIEW,
    sortOrder: undefined,
    attributes: {
      refreshInterval: 120
    }
  },
  [WIDGET_BLOCK_TYPE.SERVICE_UPTIME_CHART]: {
    id: undefined,
    type: WIDGET_BLOCK_TYPE.SERVICE_UPTIME_CHART,
    sortOrder: undefined,
    attributes: {
      title: undefined,
      scale: undefined,
      service: undefined,
      useAllServices: undefined,
      refreshInterval: 60
    }
  }
}
