import { useState } from 'react';
import classnames from 'classnames';
import { getFormattedDateTime, getStatusIconClasses, nl2br } from './logic';
import MaintenanceItemUpdates from './MaintenanceItemUpdates';
import MaintenanceItemServices from './MaintenanceItemServices';

const MaintenanceListItem = ({maintenance}) => {
  const [isOpen, setIsOpen] = useState(false);

  const toggleDetails = () => {
    setIsOpen(!isOpen);
  }

  const maintenanceClasses = [];
  const messageClasses = [];

  if (isOpen)
    maintenanceClasses.push('is-open');
  else
    messageClasses.push('ellipsis-3');

  const maintenanceStatusClass = getStatusIconClasses(maintenance.statusType);

  return <div className={classnames('maintenance-list-item', maintenanceClasses)}>
    <a href={'/maintenance/' + maintenance.id}>
      <h3 className={classnames('maintenance-subject', maintenanceStatusClass)}>
        {maintenance.name}
        <span className={classnames('status-name', 'status-name-' + maintenance.statusType)}>{maintenance.statusName}</span>
      </h3>
    </a>
    <div className="maintenance-field">
      <span className="maintenance-field-label">Scheduled For:</span>
      <span>{getFormattedDateTime(maintenance.scheduledFor)}</span>
    </div>
    <div className="maintenance-field">
      <p className={classnames(messageClasses)}>{nl2br(maintenance.purpose)}</p>
    </div>
    <div className="maintenance-field maintenance-details">
      <div className="maintenance-field">
        <span className="maintenance-field-label">Anticipated End:</span>
        <span>{getFormattedDateTime(maintenance.anticipatedEnd)}</span>
      </div>
      <div className="maintenance-field">
        <span className="maintenance-field-label">Posted By:</span>
        <span>{maintenance.createdBy}</span>
      </div>
      <MaintenanceItemServices services={maintenance.services}/>
      <MaintenanceItemUpdates updates={maintenance.updates.reverse()}/>
    </div>
    <button className="maintenance-details-toggle" onClick={toggleDetails}></button>
  </div>
}

export default MaintenanceListItem;