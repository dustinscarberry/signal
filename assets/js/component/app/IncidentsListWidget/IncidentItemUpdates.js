import React from 'react';
import IncidentItemUpdate from './IncidentItemUpdate';

const IncidentItemUpdates = (props) =>
{
  let updateNodes = undefined;
  if (props.updates.length != 0)
  {
    updateNodes = props.updates.map(update =>
    {
      return <IncidentItemUpdate key={update.id} update={update}/>;
    });

    updateNodes = updateNodes.reverse();
  }
  else
    updateNodes = 'N/A';

  return (
    <div className="incident-updates">
      <span className="incident-field-label">Updates:</span>
      {updateNodes}
    </div>
  );
}

export default IncidentItemUpdates;
