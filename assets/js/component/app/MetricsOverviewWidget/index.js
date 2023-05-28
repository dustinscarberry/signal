import { useState, useEffect } from 'react';
import { isOk } from '../../../logic/utils';
import { fetchWidgetData } from './logic';
import Loader from '../../shared/Loader';
import View from './View';

const MetricsOverviewWidget = (props) => {
  const [activeIncidents, setActiveIncidents] = useState();
  const [scheduledMaintenance, setScheduledMaintenance] = useState();
  const [daysSinceLastIncident, setDaysSinceLastIncident] = useState();
  const [refreshInterval, setRefreshInterval] = useState();

  let refreshTimer;

  useEffect(() => {
    load();
  }, []);

  useEffect(() => {
    setRefresh();
  }, [refreshInterval]);

  const load = async () => {
    const rsp = await fetchWidgetData(props.id);

    if (isOk(rsp)) {
      const data = rsp.data.data;
      const attributes = data.options.attributes;

      setActiveIncidents(data.activeIncidents);
      setScheduledMaintenance(data.scheduledMaintenance);
      setDaysSinceLastIncident(data.daysSinceLastIncident);
      setRefreshInterval(attributes.refreshInterval || 120);
    }
  }

  const setRefresh = () => {
    if (!refreshInterval * 1000) return;
    
    if (refreshTimer)
      clearInterval(refreshTimer);

    refreshTimer = setInterval(() => {  
      load();
    }, refreshInterval * 1000);
  }

  if (
    activeIncidents == undefined ||
    scheduledMaintenance == undefined ||
    daysSinceLastIncident == undefined
  )
    return <Loader/>

  return <View
    activeIncidents={activeIncidents}
    scheduledMaintenance={scheduledMaintenance}
    daysSinceLastIncident={daysSinceLastIncident}
  />
}

export default MetricsOverviewWidget;