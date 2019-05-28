import React from 'react';
import axios from 'axios';
import { isValidResponse } from './actions';
import Loader from '../../shared/Loader';
import View from './View';

class ServiceUptimeChartWidget extends React.Component
{
  constructor(props)
  {
    super(props);

    this.state = {
      dataPoints: undefined,
      title: undefined,
      scale: undefined,
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
        dataPoints: data.dataPoints,
        title: attributes.title,
        scale: attributes.scale,
        refreshInterval: attributes.refreshInterval || 60
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
    if (!this.state.dataPoints)
      return <Loader/>;

    return <View
      data={this.state.dataPoints}
      scale={this.state.scale}
      title={this.state.title}
    />;
  }
}

export default ServiceUptimeChartWidget;
