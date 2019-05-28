import React from 'react';
import WidgetBlock from '../WidgetBlock';
import FormGroup from '../../../shared/FormGroup';
import Label from '../../../shared/Label';
import TextInput from '../../../shared/TextInput';
import NumberInput from '../../../shared/NumberInput';

class IncidentsListWidgetBlock extends React.Component
{
  constructor(props)
  {
    super(props);

    this.changeTitle = this.changeTitle.bind(this);
    this.changeRefreshInterval = this.changeRefreshInterval.bind(this);

    if (this.props.widget.isNew)
      this.props.toggleIsSaved(false);
  }

  changeTitle(e)
  {
    this.props.updateAttributes({
      title: e.target.value
    });

    this.props.toggleIsSaved(false);
  }

  changeRefreshInterval(e)
  {
    const refreshInterval = e.target.value;

    this.props.updateAttributes({
      refreshInterval: refreshInterval
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
            handleChange={this.changeTitle}
          />
        </FormGroup>
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

export default IncidentsListWidgetBlock;
