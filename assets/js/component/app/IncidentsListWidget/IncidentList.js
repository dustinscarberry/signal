import IncidentItem from './IncidentItem';

const IncidentList = ({incidents}) => {
  return <div className="incident-list">
    {incidents.map(incident => {
      return <IncidentItem key={incident.id} incident={incident}/>
    })}
  </div>
}

export default IncidentList;