import MaintenanceItem from './MaintenanceItem';

const MaintenanceList = (props) => {
  const maintenanceNodes = props.maintenance.map(maintenance => {
    return <MaintenanceItem key={maintenance.id} maintenance={maintenance}/>
  });

  return <div className="maintenance-list">
    {maintenanceNodes}
  </div>
}

export default MaintenanceList;
