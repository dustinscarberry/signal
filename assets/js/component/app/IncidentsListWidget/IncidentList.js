import React from 'react';
import IncidentItem from './IncidentItem';

const IncidentList = (props) =>
{
  const incidentNodes = props.incidents.map(incident =>
  {
    return <IncidentItem key={incident.id} incident={incident}/>;
  });

  return (
    <div className="incident-list">
      {incidentNodes}
    </div>
  );
}

export default IncidentList;
