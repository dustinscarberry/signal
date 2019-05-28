import React from 'react';
import axios from 'axios';
import { isValidResponse } from './actions';
import Loader from '../../shared/Loader';
import View from './View';

class ServiceStatusOverviewWidget extends React.Component
{
  constructor(props)
  {
    super(props);

    this.state = {
      serviceStatuses: undefined
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
        serviceStatuses: data.serviceStatuses,
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

  getMessageType()
  {
    const typeRanking = [
      'ok',
      'offline',
      'issue',
      'error'
    ];

    var typeRank = 0;

    for (status of this.state.serviceStatuses)
    {
      let newRank = typeRanking.indexOf(status);
      if (newRank > typeRank)
        typeRank = newRank;

      if (typeRank == this.state.serviceStatuses.length - 1)
        break;
    }

    return typeRanking[typeRank];
  }

  getMessageText(type)
  {
    let messageText = '';

    if (type == 'ok')
      messageText = 'All Services Operational';
    else if (type == 'issue')
      messageText = 'Some Services Experiencing Issues';
    else if (type == 'error')
      messageText = 'Some Services Experiencing Major Outages';
    else if (type == 'offline')
      messageText = 'Some Services Are Offline';

    return messageText;
  }

  getStatusClasses(type)
  {
    const classes = [''];

    if (type == 'ok')
      classes.push('status-overview-ok');
    else if (type == 'issue')
      classes.push('status-overview-issue');
    else if (type == 'error')
      classes.push('status-overview-error');
    else if (type == 'offline')
      classes.push('status-overview-offline');

    return classes;
  }

  render()
  {
    if (!this.state.serviceStatuses)
      return <Loader/>;

    const type = this.getMessageType();

    return <View
      message={this.getMessageText(type)}
      statusClasses={this.getStatusClasses(type)}
    />;
  }
}

export default ServiceStatusOverviewWidget;
