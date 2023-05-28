import { useState, useEffect } from 'react';
import { isOk } from '../../../logic/utils';
import { fetchWidgetData } from './logic';
import Loader from '../../shared/Loader';
import View from './View';

const IncidentsListWidget = ({id}) => {
  const [overviewOnly, setOverviewOnly] = useState();
  const [title, setTitle] = useState();
  const [incidents, setIncidents] = useState();
  const [refreshInterval, setRefreshInterval] = useState();

  let refreshTimer;

  useEffect(() => {
    load();
  }, []);

  useEffect(() => {
    setRefresh();
  }, [refreshInterval]);

  const load = async () => {
    const rsp = await fetchWidgetData(id);

    if (isOk(rsp)) {
      const data = rsp.data.data;
      const attributes = data.options.attributes;

      setOverviewOnly(attributes.overviewOnly);
      setTitle(attributes.title);
      setIncidents(data.incidents);
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

  if (!incidents)
    return <Loader/>

  return <View
    overviewOnly={overviewOnly}
    title={title}
    incidents={incidents}
  />
}

export default IncidentsListWidget;