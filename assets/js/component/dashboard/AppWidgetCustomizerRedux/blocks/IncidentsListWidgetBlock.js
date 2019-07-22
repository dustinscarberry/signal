import React from 'react';
import WidgetBlock from '../WidgetBlock';
import FormGroup from '../../../shared/FormGroup';
import Label from '../../../shared/Label';
import TextInput from '../../../shared/TextInput';
import NumberInput from '../../../shared/NumberInput';
import SelectBox from '../../../shared/SelectBox';

class IncidentsListWidgetBlock extends React.Component
{
  constructor(props)
  {
    super(props);

    this.changeTitle = this.changeTitle.bind(this);
    this.changeTimeframe = this.changeTimeframe.bind(this);
    this.changeMaxItems = this.changeMaxItems.bind(this);
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

  changeTimeframe(e)
  {
    this.props.updateAttributes({
      timeframe: e.target.value
    });

    this.props.toggleIsSaved(false);
  }

  changeMaxItems(e)
  {
    this.props.updateAttributes({
      maxItems: e.target.value
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
          <Label title="Title"/>
          <TextInput
            value={this.props.widget.attributes.title}
            handleChange={this.changeTitle}
          />
        </FormGroup>
        <FormGroup>
          <Label title="Timeframe"/>
          <SelectBox
            value={this.props.widget.attributes.timeframe}
            options={[
              {key: 'all', value: 'All Incidents'},
              {key: 'past', value: 'Past Incidents'}
            ]}
            handleChange={this.changeTimeframe}
            useBlank={false}
          />
        </FormGroup>
        <FormGroup>
          <Label title="Max Items" hint="max incidents to show"/>
          <NumberInput
            value={this.props.widget.attributes.maxItems}
            handleChange={this.changeMaxItems}
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
