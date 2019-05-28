import React from 'react';
import axios from 'axios';
import { isValidResponse } from './actions';
import Loader from '../../shared/Loader';
import View from './View';

class IncidentsListWidget extends React.Component
{
  constructor(props)
  {
    super(props);

    this.state = {
      loading: true,
      overviewOnly: undefined,
      title: undefined,
      incidents: undefined
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

  render()
  {
    if (!this.state.incidents)
      return <Loader/>;

    return <View {...this.state}/>;
  }
}

export default IncidentsListWidget;
