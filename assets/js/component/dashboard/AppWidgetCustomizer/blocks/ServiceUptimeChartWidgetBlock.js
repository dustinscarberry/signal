import { useState, useEffect } from 'react';
import { isOk } from '../../../../logic/utils';
import FormGroup from '../../../shared/FormGroup';
import Label from '../../../shared/Label';
import TextInput from '../../../shared/TextInput';
import NumberInput from '../../../shared/NumberInput';
import SelectBox from '../../../shared/SelectBox';
import Loader from '../../../shared/Loader';
import axios from 'axios';

const ServiceUptimeChartWidgetBlock = ({widget, handleUpdateAttributes, toggleIsSaved}) => {
  const [services, setServices] = useState();

  useEffect(() => {
    if (widget.isNew)
      toggleIsSaved(false);

    loadSelectionData();
  }, []);

  const changeValue = (e) => {
    handleUpdateAttributes({
      [e.target.name]: e.target.value
    });

    toggleIsSaved(false);
  }

  const loadSelectionData = async () => {
    const rsp = await axios.get('/api/v1/services');

    if (isOk(rsp)) {
      let services = rsp.data.data.map(item => {return {key: item.id, value: item.name}});
      services.unshift({key: 'all', value: 'All Services'});
      setServices(services);
    }
  }

  if (!services)
    return <Loader/>

  return <div>
    <FormGroup>
      <Label title="Title"/>
      <TextInput
        value={widget.attributes.title}
        handleChange={changeValue}
        name="title"
      />
    </FormGroup>
    <FormGroup>
      <Label title="Scale" hint="Time Scale"/>
      <SelectBox
        value={widget.attributes.scale}
        options={[
          {key: 'day', value: 'Day'},
          {key: 'hour', value: 'Hour'},
          {key: 'minute', value: 'Minute'}
        ]}
        handleChange={changeValue}
        name="scale"
      />
    </FormGroup>
    <FormGroup>
      <Label title="Service"/>
      <SelectBox
        value={widget.attributes.service}
        options={services}
        handleChange={changeValue}
        useBlank={false}
        name="service"
      />
    </FormGroup>
    <FormGroup>
      <Label title="Refresh Interval" hint="in seconds"/>
      <NumberInput
        value={widget.attributes.refreshInterval}
        handleChange={changeValue}
        name="refreshInterval"
      />
    </FormGroup>
  </div>
}

export default ServiceUptimeChartWidgetBlock;