import React from 'react';
import axios from 'axios';
import { isValidResponse } from './actions';
import Loader from '../../shared/Loader';
import View from './View';

class MetricsOverviewWidget extends React.Component
{
  constructor(props)
  {
    super(props);

    this.state = {
      activeIncidents: undefined,
      scheduledMaintenance: undefined,
      daysSinceLastIncident: undefined,
      refreshInterval: undefined
    };

    this.refreshInterval = undefined;
  }

  componentDidMount()
  {
    this.load();
  }

  async load()
  {
    const rsp = await axios.get(
      '/api/v1/widgetsdata/' + this.props.id
    );

    if (isValidResponse(rsp))
    {
      const data = rsp.data.data;
      const attributes = data.options.attributes;

      await this.setState({
        activeIncidents: data.activeIncidents,
        scheduledMaintenance: data.scheduledMaintenance,
        daysSinceLastIncident: data.daysSinceLastIncident,
        refreshInterval: attributes.refreshInterval || 120
      });

      this.setRefresh();
    }
  }

  setRefresh()
  {
    if (this.refreshInterval)
      clearInterval(this.refreshInterval);

    this.refreshInterval = setInterval(() => {
      this.load();
    }, this.state.refreshInterval * 1000);
  }

  render()
  {
    if (
      this.state.activeIncidents == undefined ||
      this.state.scheduledMaintenance == undefined ||
      this.state.daysSinceLastIncident == undefined
    )
      return <Loader/>;

    return <View {...this.state}/>;
  }
}

export default MetricsOverviewWidget;
