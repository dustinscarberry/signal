import React from 'react';
import axios from 'axios';
import { isValidResponse } from './actions';
import Loader from '../../shared/Loader';
import View from './View';

class PastFutureLinksWidget extends React.Component
{
  constructor(props)
  {
    super(props);

    this.state = {
      loading: true,
      showPastMaintenance: undefined,
      showFutureMaintenance: undefined,
      showPastIncidents: undefined
    };
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
        showPastMaintenance: attributes.showPastMaintenance,
        showFutureMaintenance: attributes.showFutureMaintenance,
        showPastIncidents: attributes.showPastIncidents,
        loading: false
      });
    }
  }

  render()
  {
    if (this.state.loading)
      return <Loader/>;

    return <View {...this.state}/>;
  }
}

export default PastFutureLinksWidget;
