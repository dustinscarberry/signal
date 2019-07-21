import React from 'react';
import WidgetBlock from '../WidgetBlock';
import FormGroup from '../../../shared/FormGroup';
import Label from '../../../shared/Label';
import Toggle from '../../../shared/Toggle';

class PastFutureLinksWidgetBlock extends React.Component
{
  constructor(props)
  {
    super(props);

    this.changeShowPastMaintenance = this.changeShowPastMaintenance.bind(this);
    this.changeShowFutureMaintenance = this.changeShowFutureMaintenance.bind(this);
    this.changeShowPastIncidents = this.changeShowPastIncidents.bind(this);
  }

  changeShowPastMaintenance(e)
  {
    this.props.updateAttributes({
      showPastMaintenance: e.target.checked
    });

    this.props.toggleIsSaved(false);
  }

  changeShowFutureMaintenance(e)
  {
    this.props.updateAttributes({
      showFutureMaintenance: e.target.checked
    });

    this.props.toggleIsSaved(false);
  }

  changeShowPastIncidents(e)
  {
    this.props.updateAttributes({
      showPastIncidents: e.target.checked
    });

    this.props.toggleIsSaved(false);
  }

  render()
  {
    return (
      <div>
        <FormGroup>
          <Label title="Show Past Maintenance" hint=""/>
          <Toggle
            value={this.props.widget.attributes.showPastMaintenance}
            handleChange={this.changeShowPastMaintenance}
          />
        </FormGroup>
        <FormGroup>
          <Label title="Show Future Maintenance" hint=""/>
          <Toggle
            value={this.props.widget.attributes.showFutureMaintenance}
            handleChange={this.changeShowFutureMaintenance}
          />
        </FormGroup>
        <FormGroup>
          <Label title="Show Past Incidents" hint=""/>
          <Toggle
            value={this.props.widget.attributes.showPastIncidents}
            handleChange={this.changeShowPastIncidents}
          />
        </FormGroup>
      </div>
    );
  }
}

export default PastFutureLinksWidgetBlock;
