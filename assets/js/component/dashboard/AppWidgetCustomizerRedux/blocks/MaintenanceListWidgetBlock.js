import React from 'react';
import autobind from 'autobind-decorator';
import WidgetBlock from '../WidgetBlock';
import FormGroup from '../../../shared/FormGroup';
import Label from '../../../shared/Label';
import TextInput from '../../../shared/TextInput';
import NumberInput from '../../../shared/NumberInput';
import SelectBox from '../../../shared/SelectBox';

class MaintenanceListWidgetBlock extends React.Component
{
  constructor(props)
  {
    super(props);

    if (this.props.widget.isNew)
      this.props.toggleIsSaved(false);
  }

  @autobind
  changeValue(e)
  {
    this.props.updateAttributes({
      [e.target.name]: e.target.value
    });

    this.props.toggleIsSaved(false);
  }

  render()
  {
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
          <Label title="Timeframe"/>
          <SelectBox
            value={this.props.widget.attributes.timeframe}
            options={[
              {key: 'all', value: 'All Maintenance'},
              {key: 'scheduled', value: 'Scheduled Maintenance'}
            ]}
            handleChange={this.changeValue}
            useBlank={false}
            name="timeframe"
          />
        </FormGroup>
        <FormGroup>
          <Label title="Max Items" hint="max maintenances to show"/>
          <NumberInput
            value={this.props.widget.attributes.maxItems}
            handleChange={this.changeValue}
            name="maxItems"
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

export default MaintenanceListWidgetBlock;
