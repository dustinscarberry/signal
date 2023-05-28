import { useState, useEffect } from 'react';
import { isOk } from '../../../logic/utils';
import { fetchWidgetData } from './logic';
import Loader from '../../shared/Loader';
import View from './View';

const PastFutureLinksWidget = ({id}) => {
  const [isLoading, setIsLoading] = useState(true);
  const [showPastMaintenance, setShowPastMainteance] = useState();
  const [showFutureMaintenance, setShowFutureMaintenance] = useState();
  const [showPastIncidents, setShowPastIncidents] = useState();
  
  useEffect(() => {
    load();
  }, []);
 
  const load = async () => {
    const rsp = await fetchWidgetData(id);
    
    if (isOk(rsp)) {
      const data = rsp.data.data;
      const attributes = data.options.attributes;

      setShowPastMainteance(attributes.showPastMaintenance);
      setShowFutureMaintenance(attributes.showFutureMaintenance);
      setShowPastIncidents(attributes.showPastIncidents);
      setIsLoading(false);
    }
  }

  if (isLoading)
    return <Loader/>

  return <View
    showPastMaintenance={showPastMaintenance}
    showFutureMaintenance={showFutureMaintenance}
    showPastIncidents={showPastIncidents}
  /> 
}

export default PastFutureLinksWidget;