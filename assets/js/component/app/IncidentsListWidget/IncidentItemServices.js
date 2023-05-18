const IncidentItemServices = ({services}) => {
  const serviceList = services.map(service => service.serviceName);

  return <div className="incident-field">
    <span className="incident-field-label">Affected Services:</span>
    <span>{serviceList.join(', ')}</span>
  </div>
}

export default IncidentItemServices;
