import ContentBlock from '../ContentBlock';

const View = ({activeIncidents, scheduledMaintenance, daysSinceLastIncident}) => {
  return <ContentBlock>
    <div className="container-fluid">
      <div className="metrics-overview-widget">
        <div className="row">
          <div className="col-md-4 item">
            <span className="metric">{activeIncidents}</span>
            <span className="metric-description">Active Incidents</span>
          </div>
          <div className="col-md-4 item">
            <span className="metric">{scheduledMaintenance}</span>
            <span className="metric-description">Scheduled Maintenance</span>
          </div>
          <div className="col-md-4 item">
            <span className="metric">{daysSinceLastIncident}</span>
            <span className="metric-description">Days Since Last Incident</span>
          </div>
        </div>
      </div>
    </div>
  </ContentBlock>
}

View.defaultProps = {
  activeIncidents: undefined,
  scheduledMaintenance: undefined,
  daysSinceLastIncident: undefined
}

export default View;