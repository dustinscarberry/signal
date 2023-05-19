import { Component } from 'react';
import axios from 'axios';
import { isValidResponse } from './actions';
import Loader from '../../shared/Loader';
import View from './View';

class ServicesListWidget extends Component
{
  constructor(props) {
    super(props);

    this.state = {
      layout: undefined,
      useGroups: undefined,
      loading: true,
      services: undefined
    };

    this.refreshInterval = undefined;
  }

  componentDidMount() {
    this.load();
  }

  async load() {
    const rsp = await axios.get(
      '/api/v1/widgetsdata/' + this.props.id
    );

    if (isValidResponse(rsp))
    {
      const data = rsp.data.data;
      const attributes = data.options.attributes;

      await this.setState({
        layout: attributes.layout,
        useGroups: attributes.useGroups,
        services: data.services,
        refreshInterval: attributes.refreshInterval || 120
      });

      this.setRefresh();
    }
  }

  setRefresh() {
    if (this.refreshInterval)
      clearInterval(this.refreshInterval);

    this.refreshInterval = setInterval(() => {
      this.load();
    }, this.state.refreshInterval * 1000);
  }

  render() {
    if (!this.state.layout)
      return <Loader/>

    return <View {...this.state}/>
  }
}

export default ServicesListWidget;
