import { useEffect } from 'react';
import FormGroup from '../../../shared/FormGroup';
import Label from '../../../shared/Label';
import NumberInput from '../../../shared/NumberInput';

const MetricsOverviewWidgetBlock = ({widget, handleUpdateAttributes, toggleIsSaved}) => {
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
      <Label title="Refresh Interval" hint="in seconds"/>
      <NumberInput
        value={widget.attributes.refreshInterval}
        handleChange={changeValue}
        name="refreshInterval"
      />
    </FormGroup>
  </div>
}

export default MetricsOverviewWidgetBlock;