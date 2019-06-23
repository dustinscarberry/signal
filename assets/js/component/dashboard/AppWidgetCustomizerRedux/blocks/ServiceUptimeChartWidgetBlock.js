import React from 'react';
import WidgetBlock from '../WidgetBlock';
import FormGroup from '../../../shared/FormGroup';
import Label from '../../../shared/Label';
import TextInput from '../../../shared/TextInput';
import NumberInput from '../../../shared/NumberInput';
import SelectBox from '../../../shared/SelectBox';
import Loader from '../../../shared/Loader';
import axios from 'axios';

class ServiceUptimeChartWidgetBlock extends React.Component
{
  constructor(props)
  {
    super(props);

    this.state = {
      services: undefined
    };

    this.changeTitle = this.changeTitle.bind(this);
    this.changeScale = this.changeScale.bind(this);
    this.changeRefreshInterval = this.changeRefreshInterval.bind(this);
    this.changeService = this.changeService.bind(this);

    if (this.props.widget.isNew)
      this.props.toggleIsSaved(false);

    this.loadSelectionData();
  }

  async loadSelectionData()
  {
    const rsp = await axios.get('/api/v1/services');

    if (rsp.status == 200 && !rsp.data.error)
    {
      let services = rsp.data.data.map(item => {return {key: item.guid, value: item.name}});
      services.unshift({key: 'all', value: 'All Services'});

      this.setState({services: services});
    }
  }

  changeTitle(e)
  {
    this.props.updateAttributes({
      title: e.target.value
    });

    this.props.toggleIsSaved(false);
  }

  changeScale(e)
  {
    this.props.updateAttributes({
      scale: e.target.value
    });

    this.props.toggleIsSaved(false);
  }

  changeRefreshInterval(e)
  {
    this.props.updateAttributes({
      refreshInterval: e.target.value
    });

    this.props.toggleIsSaved(false);
  }

  changeService(e)
  {
    this.props.updateAttributes({
      service: e.target.value
    });

    this.props.toggleIsSaved(false);
  }

  render()
  {
    if (!this.state.services)
      return <Loader/>;

    return (
      <div>
        <FormGroup>
          <Label title="Title"/>
          <TextInput
            value={this.props.widget.attributes.title}
            handleChange={this.changeTitle}
          />
        </FormGroup>
        <FormGroup>
          <Label title="Scale" hint="Time Scale"/>
          <SelectBox
            value={this.props.widget.attributes.scale}
            options={[
              {key: 'day', value: 'Day'},
              {key: 'hour', value: 'Hour'},
              {key: 'minute', value: 'Minute'}
            ]}
            handleChange={this.changeScale}
          />
        </FormGroup>
        <FormGroup>
          <Label title="Service"/>
          <SelectBox
            value={this.props.widget.attributes.service}
            options={this.state.services}
            handleChange={this.changeService}
            useBlank={false}
          />
        </FormGroup>
        <FormGroup>
          <Label title="Refresh Interval" hint="in seconds"/>
          <NumberInput
            value={this.props.widget.attributes.refreshInterval}
            handleChange={this.changeRefreshInterval}
          />
        </FormGroup>
      </div>
    );
  }
}

export default ServiceUptimeChartWidgetBlock;
