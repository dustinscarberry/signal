import IncidentItemUpdate from './IncidentItemUpdate';

const IncidentItemUpdates = ({updates}) => {
  let updateNodes;
  if (updates.length != 0) {
    updateNodes = updates.map(update => {
      return <IncidentItemUpdate key={update.id} update={update}/>
    });

    updateNodes = updateNodes.reverse();
  } else
    updateNodes = 'N/A';

  return <div className="incident-updates">
    <span className="incident-field-label">Updates:</span>
    {updateNodes}
  </div>
}

export default IncidentItemUpdates;