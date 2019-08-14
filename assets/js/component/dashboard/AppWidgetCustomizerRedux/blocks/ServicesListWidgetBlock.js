import React from 'react';
import autobind from 'autobind-decorator';
import WidgetBlock from '../WidgetBlock';
import FormGroup from '../../../shared/FormGroup';
import Label from '../../../shared/Label';
import TextInput from '../../../shared/TextInput';
import NumberInput from '../../../shared/NumberInput';
import SelectBox from '../../../shared/SelectBox';
import Toggle from '../../../shared/Toggle';

class ServicesListWidgetBlock extends React.Component
{
  constructor(props)
  {
    super(props);
  }

  @autobind
  changeValue(e)
  {
    this.props.updateAttributes({
      [e.target.name]: e.target.value
    });

    this.props.toggleIsSaved(false);
  }

  @autobind
  changeCheckboxValue(e)
  {
    this.props.updateAttributes({
      [e.target.name]: e.target.checked
    });

    this.props.toggleIsSaved(false);
  }

  render()
  {
    return (
      <div>
        <FormGroup>
          <Label title="Layout" hint="Services layout"/>
          <SelectBox
            value={this.props.widget.attributes.layout}
            options={[
              {key: 'onecolumn', value: 'One Column'},
              {key: 'twocolumn', value: 'Two Column'}
            ]}
            handleChange={this.changeValue}
            name="layout"
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
        <FormGroup>
          <Label title="Group Services"/>
          <Toggle
            value={this.props.widget.attributes.useGroups}
            handleChange={this.changeCheckboxValue}
            name="useGroups"
          />
        </FormGroup>
      </div>
    );
  }
}

export default ServicesListWidgetBlock;
