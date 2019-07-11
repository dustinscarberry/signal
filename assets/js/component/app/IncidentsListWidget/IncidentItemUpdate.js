import React from 'react';
import { getFormattedDateTime, getStatusIconClasses, nl2br } from './actions';
import classnames from 'classnames';

const IncidentItemUpdate = (props) =>
{
  const { update } = props;

  const updateStatusClass = getStatusIconClasses(update.statusType);

  return (
    <div className="incident-update">
      <span className={classnames('incident-update-occurred', updateStatusClass)}>{getFormattedDateTime(update.created)}</span>
      <p>{nl2br(update.message)}</p>
    </div>
  );
}

export default IncidentItemUpdate;
