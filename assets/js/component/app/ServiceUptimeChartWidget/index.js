import { useState, useEffect } from 'react';
import { isOk } from '../../../logic/utils';
import { fetchWidgetData } from './logic';
import Loader from '../../shared/Loader';
import View from './View';

const ServiceUptimeChartWidget = ({id}) => {
  const [dataPoints, setDataPoints] = useState();
  const [title, setTitle] = useState();
  const [scale, setScale] = useState();
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

      setDataPoints(data.dataPoints);
      setTitle(attributes.title);
      setScale(attributes.scale);
      setRefreshInterval(attributes.refreshInterval || 60);
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

  if (!dataPoints)
    return <Loader/>

  return <View
    data={dataPoints}
    scale={scale}
    title={title}
  />
}

export default ServiceUptimeChartWidget;