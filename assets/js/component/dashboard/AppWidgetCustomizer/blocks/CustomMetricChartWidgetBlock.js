import { useState, useEffect } from 'react';
import { isOk } from '../../../../logic/utils';
import FormGroup from '../../../shared/FormGroup';
import Label from '../../../shared/Label';
import TextInput from '../../../shared/TextInput';
import NumberInput from '../../../shared/NumberInput';
import SelectBox from '../../../shared/SelectBox';
import Loader from '../../../shared/Loader';
import axios from 'axios';

const CustomMetricChartWidgetBlock = ({widget, handleUpdateAttributes, toggleIsSaved}) => {
  const [metrics, setMetrics] = useState();

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
    const rsp = await axios.get('/api/v1/custommetrics');

    if (isOk(rsp)) {
      let metrics = rsp.data.data.map(item => {return {key: item.id, value: item.name}});
      metrics.unshift({key: '', value: ''});
      setMetrics(metrics);
    }
  }

  if (!metrics)
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
      <Label title="Y Axis Legend"/>
      <TextInput
        value={widget.attributes.yLegend}
        handleChange={changeValue}
        name="yLegend"
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
      <Label title="Metric"/>
      <SelectBox
        value={widget.attributes.metric}
        options={metrics}
        handleChange={changeValue}
        useBlank={false}
        name="metric"
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

export default CustomMetricChartWidgetBlock;