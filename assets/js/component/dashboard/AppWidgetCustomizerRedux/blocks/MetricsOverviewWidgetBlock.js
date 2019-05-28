import React from 'react';
import WidgetBlock from '../WidgetBlock';
import FormGroup from '../../../shared/FormGroup';
import Label from '../../../shared/Label';
import NumberInput from '../../../shared/NumberInput';

class MetricsOverviewWidgetBlock extends React.Component
{
  constructor(props)
  {
    super(props);

    this.changeRefreshInterval = this.changeRefreshInterval.bind(this);

    if (this.props.widget.isNew)
      this.props.toggleIsSaved(false);
  }

  changeRefreshInterval(e)
  {
    this.props.updateAttributes({
      refreshInterval: e.target.value
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
            handleChange={this.changeRefreshInterval}
          />
        </FormGroup>
      </div>
    );
  }
}

export default MetricsOverviewWidgetBlock;
