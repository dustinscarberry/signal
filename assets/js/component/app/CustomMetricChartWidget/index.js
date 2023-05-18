import { Component } from 'react';
import axios from 'axios';
import { isValidResponse } from './actions';
import Loader from '../../shared/Loader';
import View from './View';

class CustomMetricChartWidget extends Component
{
  constructor(props) {
    super(props);

    this.state = {
      dataPoints: undefined,
      title: undefined,
      yLegend: undefined,
      scale: undefined,
      refreshInterval: undefined
    };

    this.refreshInterval = undefined;
  }

  componentDidMount() {
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
        yLegend: attributes.yLegend,
        scale: attributes.scale,
        refreshInterval: attributes.refreshInterval || 60,
        scaleStart: data.scaleStart,
        scaleEnd: data.scaleEnd
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
      yLegend={this.state.yLegend}
      scaleStart={this.state.scaleStart}
      scaleEnd={this.state.scaleEnd}
    />;
  }
}

export default CustomMetricChartWidget;
