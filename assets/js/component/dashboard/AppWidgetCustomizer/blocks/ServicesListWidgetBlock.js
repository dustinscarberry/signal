import FormGroup from '../../../shared/FormGroup';
import Label from '../../../shared/Label';
import NumberInput from '../../../shared/NumberInput';
import SelectBox from '../../../shared/SelectBox';
import Toggle from '../../../shared/Toggle';

const ServicesListWidgetBlock = ({widget, handleUpdateAttributes, toggleIsSaved}) => {
  const changeValue = (e) => {
    handleUpdateAttributes({
      [e.target.name]: e.target.value
    });

    toggleIsSaved(false);
  }

  const changeCheckboxValue = (e) => {
    handleUpdateAttributes({
      [e.target.name]: e.target.checked
    });

    toggleIsSaved(false);
  }

  return <div>
    <FormGroup>
      <Label title="Layout" hint="Services layout"/>
      <SelectBox
        value={widget.attributes.layout}
        options={[
          {key: 'onecolumn', value: 'One Column'},
          {key: 'twocolumn', value: 'Two Column'}
        ]}
        handleChange={changeValue}
        name="layout"
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
    <FormGroup>
      <Label title="Group Services"/>
      <Toggle
        value={widget.attributes.useGroups}
        handleChange={changeCheckboxValue}
        name="useGroups"
      />
    </FormGroup>
  </div>
}

export default ServicesListWidgetBlock;