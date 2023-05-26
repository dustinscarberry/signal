import classnames from 'classnames';
import { getFormattedDateTime, getStatusIconClasses, nl2br } from './logic';

const IncidentItemUpdate = ({update}) => {
  const updateStatusClass = getStatusIconClasses(update.statusType);

  return <div className="incident-update">
    <span className={classnames('incident-update-occurred', updateStatusClass)}>{getFormattedDateTime(update.created)}</span>
    <p>{nl2br(update.message)}</p>
  </div>
}

export default IncidentItemUpdate;
