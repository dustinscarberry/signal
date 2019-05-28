import React from 'react';
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

    this.changeLayout = this.changeLayout.bind(this);
    this.changeUseGroups = this.changeUseGroups.bind(this);
    this.changeRefreshInterval = this.changeRefreshInterval.bind(this);
  }

  changeLayout(e)
  {
    this.props.updateAttributes({
      layout: e.target.value
    });

    this.props.toggleIsSaved(false);
  }

  changeUseGroups(e)
  {
    this.props.updateAttributes({
      useGroups: e.target.checked
    });

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
          <Label title="Layout" hint="Services layout"/>
          <SelectBox
            value={this.props.widget.attributes.layout}
            options={[
              {key: 'onecolumn', value: 'One Column'},
              {key: 'twocolumn', value: 'Two Column'}
            ]}
            handleChange={this.changeLayout}
          />
        </FormGroup>
        <FormGroup>
          <Label title="Refresh Interval" hint="in seconds"/>
          <NumberInput
            value={this.props.widget.attributes.refreshInterval}
            handleChange={this.changeRefreshInterval}
          />
        </FormGroup>
        <FormGroup>
          <Label title="Group Services"/>
          <Toggle
            value={this.props.widget.attributes.useGroups}
            handleChange={this.changeUseGroups}
          />
        </FormGroup>
      </div>
    );
  }
}

export default ServicesListWidgetBlock;
