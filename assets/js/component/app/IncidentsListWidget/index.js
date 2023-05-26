import { Component } from 'react';
import axios from 'axios';
import { isOk } from '../../../logic/utils';
import Loader from '../../shared/Loader';
import View from './View';

class IncidentsListWidget extends Component
{
  constructor(props) {
    super(props);

    this.state = {
      loading: true,
      overviewOnly: undefined,
      title: undefined,
      incidents: undefined
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

    if (isOk(rsp))
    {
      const data = rsp.data.data;
      const attributes = data.options.attributes;

      await this.setState(
      {
        overviewOnly: attributes.overviewOnly,
        title: attributes.title,
        incidents: data.incidents,
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

  render() {
    const { incidents } = this.state;

    if (!incidents)
      return <Loader/>

    return <View {...this.state}/>
  }
}

export default IncidentsListWidget;
