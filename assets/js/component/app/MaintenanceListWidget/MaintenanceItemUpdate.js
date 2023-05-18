import { getFormattedDateTime, getStatusIconClasses, nl2br } from './actions';
import classnames from 'classnames';

const MaintenanceItemUpdate = (props) => {
  const { update } = props;

  const updateStatusClass = getStatusIconClasses(update.statusType);

  return <div className="maintenance-update">
    <span className={classnames('maintenance-update-occurred', updateStatusClass)}>{getFormattedDateTime(update.created)}</span>
    <p>{nl2br(update.message)}</p>
  </div>
}

export default MaintenanceItemUpdate;
