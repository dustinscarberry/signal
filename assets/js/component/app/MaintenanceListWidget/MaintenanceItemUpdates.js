import React from 'react';
import MaintenanceItemUpdate from './MaintenanceItemUpdate';

const MaintenanceItemUpdates = (props) =>
{
  let updateNodes = undefined;
  if (props.updates.length != 0)
  {
    updateNodes = props.updates.map(update =>
    {
      return <MaintenanceItemUpdate key={update.id} update={update}/>;
    });

    updateNodes = updateNodes.reverse();
  }
  else
    updateNodes = 'N/A';

  return (
    <div className="maintenance-updates">
      <span className="maintenance-field-label">Updates:</span>
      {updateNodes}
    </div>
  );
}

export default MaintenanceItemUpdates;
