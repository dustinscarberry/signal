import React from 'react';
import autobind from 'autobind-decorator';
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

    if (this.props.widget.isNew)
      this.props.toggleIsSaved(false);

    this.loadSelectionData();
  }

  @autobind
  changeValue(e)
  {
    this.props.updateAttributes({
      [e.target.name]: e.target.value
    });

    this.props.toggleIsSaved(false);
  }

  async loadSelectionData()
  {
    const rsp = await axios.get('/api/v1/services');

    if (rsp.status == 200 && !rsp.data.error)
    {
      let services = rsp.data.data.map(item => {return {key: item.id, value: item.name}});
      services.unshift({key: 'all', value: 'All Services'});

      this.setState({services: services});
    }
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
            handleChange={this.changeValue}
            name="title"
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
            handleChange={this.changeValue}
            name="scale"
          />
        </FormGroup>
        <FormGroup>
          <Label title="Service"/>
          <SelectBox
            value={this.props.widget.attributes.service}
            options={this.state.services}
            handleChange={this.changeValue}
            useBlank={false}
            name="service"
          />
        </FormGroup>
        <FormGroup>
          <Label title="Refresh Interval" hint="in seconds"/>
          <NumberInput
            value={this.props.widget.attributes.refreshInterval}
            handleChange={this.changeValue}
            name="refreshInterval"
          />
        </FormGroup>
      </div>
    );
  }
}

export default ServiceUptimeChartWidgetBlock;
