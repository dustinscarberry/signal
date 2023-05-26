import { useState, useEffect } from 'react';
import { isOk } from '../../../logic/utils';
import { fetchWidgetData } from './logic';
import Loader from '../../shared/Loader';
import View from './View';

const ServicesListWidget = (props) => {
  const [layout, setLayout] = useState();
  const [useGroups, setUseGroups] = useState();
  const [services, setServices] = useState();
  const [refreshInterval, setRefreshInterval] = useState();

  let refreshTimer;

  useEffect(() => {
    load();
  }, []);

  useEffect(() => {
    setRefresh();
  }, [refreshInterval]);

  const load =  async () => {
    const rsp = await fetchWidgetData(props.id);
    
    if (isOk(rsp)) {
      const data = rsp.data.data;
      const attributes = data.options.attributes;

      setLayout(attributes.layout);
      setUseGroups(attributes.useGroups);
      setServices(data.services);
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

  if (!layout)
    return <Loader/>

  return <View
    layout={layout}
    useGroups={useGroups}
    services={services}
  />
}

export default ServicesListWidget;