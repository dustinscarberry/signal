import MaintenanceItemUpdate from './MaintenanceItemUpdate';

const MaintenanceItemUpdates = ({updates}) => {
  let updateNodes = 'N/A';
  if (updates.length != 0) {
    updateNodes = updates.map(update => {
      return <MaintenanceItemUpdate key={update.id} update={update}/>
    });

    updateNodes = updateNodes.reverse();
  }

  return <div className="maintenance-updates">
    <span className="maintenance-field-label">Updates:</span>
    {updateNodes}
  </div>
}

export default MaintenanceItemUpdates;