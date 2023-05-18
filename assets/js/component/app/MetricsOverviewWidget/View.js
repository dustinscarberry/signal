import ContentBlock from '../ContentBlock';

const View = (props) => {
  return <ContentBlock>
    <div className="container-fluid">
      <div className="metrics-overview-widget">
        <div className="row">
          <div className="col-md-4 item">
            <span className="metric">{props.activeIncidents}</span>
            <span className="metric-description">Active Incidents</span>
          </div>
          <div className="col-md-4 item">
            <span className="metric">{props.scheduledMaintenance}</span>
            <span className="metric-description">Scheduled Maintenance</span>
          </div>
          <div className="col-md-4 item">
            <span className="metric">{props.daysSinceLastIncident}</span>
            <span className="metric-description">Days Since Last Incident</span>
          </div>
        </div>
      </div>
    </div>
  </ContentBlock>
}

View.defaultProps = {};

export default View;
