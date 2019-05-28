import React from 'react';

const IncidentItemServices = (props) =>
{
  const serviceList = props.services.map(service => service.serviceName);

  return (
    <div className="incident-field">
      <span className="incident-field-label">Affected Services:</span>
      <span>{serviceList.join(', ')}</span>
    </div>
  );
}

export default IncidentItemServices;
