import { Component } from 'react';
import FormGroup from '../../../shared/FormGroup';
import Label from '../../../shared/Label';
import Toggle from '../../../shared/Toggle';

class PastFutureLinksWidgetBlock extends Component
{
  constructor(props) {
    super(props);

    if (this.props.widget.isNew)
      this.props.toggleIsSaved(false);
  }

  changeCheckboxValue = (e) => {
    this.props.updateAttributes({
      [e.target.name]: e.target.checked
    });

    this.props.toggleIsSaved(false);
  }

  render() {
    return <div>
      <FormGroup>
        <Label title="Show Past Maintenance" hint=""/>
        <Toggle
          value={this.props.widget.attributes.showPastMaintenance}
          handleChange={this.changeCheckboxValue}
          name="showPastMaintenance"
        />
      </FormGroup>
      <FormGroup>
        <Label title="Show Future Maintenance" hint=""/>
        <Toggle
          value={this.props.widget.attributes.showFutureMaintenance}
          handleChange={this.changeCheckboxValue}
          name="showFutureMaintenance"
        />
      </FormGroup>
      <FormGroup>
        <Label title="Show Past Incidents" hint=""/>
        <Toggle
          value={this.props.widget.attributes.showPastIncidents}
          handleChange={this.changeCheckboxValue}
          name="showPastIncidents"
        />
      </FormGroup>
    </div>
  }
}

export default PastFutureLinksWidgetBlock;
