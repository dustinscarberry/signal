import { useEffect } from 'react';
import FormGroup from '../../../shared/FormGroup';
import Label from '../../../shared/Label';
import TextInput from '../../../shared/TextInput';
import NumberInput from '../../../shared/NumberInput';
import SelectBox from '../../../shared/SelectBox';

const MaintenanceListWidgetBlock = ({widget, handleUpdateAttributes, toggleIsSaved}) => {
  useEffect(() => {
    if (widget.isNew)
      toggleIsSaved(false);
  }, []);

  const changeValue = (e) => {
    handleUpdateAttributes({
      [e.target.name]: e.target.value
    });

    toggleIsSaved(false);
  }

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
      <Label title="Timeframe"/>
      <SelectBox
        value={widget.attributes.timeframe}
        options={[
          {key: 'all', value: 'All Maintenance'},
          {key: 'scheduled', value: 'Scheduled Maintenance'}
        ]}
        handleChange={changeValue}
        useBlank={false}
        name="timeframe"
      />
    </FormGroup>
    <FormGroup>
      <Label title="Max Items" hint="max maintenances to show"/>
      <NumberInput
        value={widget.attributes.maxItems}
        handleChange={changeValue}
        name="maxItems"
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

export default MaintenanceListWidgetBlock;