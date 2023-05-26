import { useState, useEffect } from 'react';
import { isOk } from '../../../logic/utils';
import { fetchWidgetData, getMessageType, getMessageText, getStatusClasses } from './logic';
import Loader from '../../shared/Loader';
import View from './View';

const ServiceStatusOverviewWidget = (props) => {
  const [serviceStatuses, setServiceStatuses] = useState();
  const [refreshInterval, setRefreshInterval] = useState();

  let refreshTimer;

  useEffect(() => {
    load();
  }, [])

  useEffect(() => {
    setRefresh();
  }, [refreshInterval]);

  const load = async () => {
    const rsp = await fetchWidgetData(props.id);

    if (isOk(rsp)) {
      const data = rsp.data.data;
      const attributes = data.options.attributes;

      setServiceStatuses(data.serviceStatuses);
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

  if (!serviceStatuses)
    return <Loader/>

  const type = getMessageType(serviceStatuses);

  return <View
    message={getMessageText(type)}
    statusClasses={getStatusClasses(type)}
  />
}

export default ServiceStatusOverviewWidget;