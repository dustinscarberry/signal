import React from 'react';
import autobind from 'autobind-decorator';
import WidgetBlock from '../WidgetBlock';
import FormGroup from '../../../shared/FormGroup';
import Label from '../../../shared/Label';
import NumberInput from '../../../shared/NumberInput';

class ServiceStatusOverviewWidgetBlock extends React.Component
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

export default ServiceStatusOverviewWidgetBlock;
