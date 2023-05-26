import { useState } from 'react';
import IncidentItemUpdates from './IncidentItemUpdates';
import IncidentItemServices from './IncidentItemServices';
import { getFormattedDateTime, getStatusIconClasses, nl2br } from './logic';
import classnames from 'classnames';

const IncidentListItem = ({incident}) => {
  const [isOpen, setIsOpen] = useState(false);

  const toggleDetails = () => {
    setIsOpen(!isOpen);
  }

  const incidentClasses = [];
  const messageClasses = [];

  if (isOpen)
    incidentClasses.push('is-open');
  else
    messageClasses.push('ellipsis-3');

  const incidentStatusClass = getStatusIconClasses(incident.statusType);

  return <div className={classnames('incident-list-item', incidentClasses)}>
    <a href={'/incident/' + incident.id}>
      <h3 className={classnames('incident-subject', incidentStatusClass)}>
        {incident.name}
        <span className={classnames('status-name', 'status-name-' + incident.statusType)}>{incident.statusName}</span>
      </h3>
    </a>
    <div className="incident-field">
      <span className="incident-field-label">Occurred:</span>
      <span>{getFormattedDateTime(incident.occurred)}</span>
    </div>
    <div className="incident-field">
      <p className={classnames(messageClasses)}>{nl2br(incident.message)}</p>
    </div>
    <div className="incident-field incident-details">
      <div className="incident-field">
        <span className="incident-field-label">Anticipated Resolution:</span>
        <span>{getFormattedDateTime(incident.anticipatedResolution)}</span>
      </div>
      <div className="incident-field">
        <span className="incident-field-label">Reported By:</span>
        <span>{incident.createdBy}</span>
      </div>
      <div className="incident-field">
        <span className="incident-field-label">Type:</span>
        <span>{incident.type}</span>
      </div>
      <IncidentItemServices services={incident.services}/>
      <IncidentItemUpdates updates={incident.updates.reverse()}/>
    </div>
    <button className="incident-details-toggle" onClick={toggleDetails}></button>
  </div>
}

export default IncidentListItem;