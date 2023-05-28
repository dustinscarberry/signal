import { useState, useEffect } from 'react';
import { isOk } from '../../../logic/utils';
import { fetchWidgetData } from './logic';
import Loader from '../../shared/Loader';
import View from './View';

const MaintenanceListWidget = ({id}) => {
  const [overviewOnly, setOverviewOnly] = useState();
  const [title, setTitle] = useState();
  const [maintenance, setMaintenance] = useState();
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
      setMaintenance(data.maintenance);
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

  if (!maintenance)
    return <Loader/>

  return <View
    title={title}
    maintenance={maintenance}
    overviewOnly={overviewOnly}
  />
}

export default MaintenanceListWidget;