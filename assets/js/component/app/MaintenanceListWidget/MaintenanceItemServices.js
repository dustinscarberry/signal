import React from 'react';

const MaintenanceItemServices = (props) =>
{
  const serviceList = props.services.map(service => service.serviceName);

  return (
    <div className="maintenance-field">
      <span className="maintenance-field-label">Affected Services:</span>
      <span>{serviceList.join(', ')}</span>
    </div>
  );
}

export default MaintenanceItemServices;
