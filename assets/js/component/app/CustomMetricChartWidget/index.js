import { useState, useEffect } from 'react';
import { isOk } from '../../../logic/utils';
import { fetchWidgetData } from './logic';
import Loader from '../../shared/Loader';
import View from './View';

const CustomMetricChartWidget = (props) => {
  const [dataPoints, setDataPoints] = useState();
  const [title, setTitle] = useState();
  const [yLegend, setYLegend] = useState();
  const [scale, setScale] = useState();
  const [scaleStart, setScaleStart] = useState();
  const [scaleEnd, setScaleEnd] = useState();
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

      setDataPoints(data.dataPoints);
      setTitle(attributes.title);
      setYLegend(attributes.yLegend);
      setScale(attributes.scale);
      setScaleStart(data.scaleStart);
      setScaleEnd(data.scaleEnd);
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
    yLegend={yLegend}
    scaleStart={scaleStart}
    scaleEnd={scaleEnd}
  />
}

export default CustomMetricChartWidget;