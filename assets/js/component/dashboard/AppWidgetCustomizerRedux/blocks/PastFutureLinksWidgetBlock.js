import { useEffect } from 'react';
import FormGroup from '../../../shared/FormGroup';
import Label from '../../../shared/Label';
import Toggle from '../../../shared/Toggle';

const PastFutureLinksWidgetBlock = ({widget, handleUpdateAttributes, toggleIsSaved}) => {
  useEffect(() => {
    if (widget.isNew)
      toggleIsSaved(false);
  }, []);

  const changeCheckboxValue = (e) => {
    handleUpdateAttributes({
      [e.target.name]: e.target.checked
    });

    toggleIsSaved(false);
  }

  return <div>
    <FormGroup>
      <Label title="Show Past Maintenance" hint=""/>
      <Toggle
        value={widget.attributes.showPastMaintenance}
        handleChange={changeCheckboxValue}
        name="showPastMaintenance"
      />
    </FormGroup>
    <FormGroup>
      <Label title="Show Future Maintenance" hint=""/>
      <Toggle
        value={widget.attributes.showFutureMaintenance}
        handleChange={changeCheckboxValue}
        name="showFutureMaintenance"
      />
    </FormGroup>
    <FormGroup>
      <Label title="Show Past Incidents" hint=""/>
      <Toggle
        value={widget.attributes.showPastIncidents}
        handleChange={changeCheckboxValue}
        name="showPastIncidents"
      />
    </FormGroup>
  </div>
}

export default PastFutureLinksWidgetBlock;