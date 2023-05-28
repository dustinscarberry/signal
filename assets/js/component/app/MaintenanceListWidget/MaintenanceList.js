import MaintenanceItem from './MaintenanceItem';

const MaintenanceList = ({maintenance}) => {
  return <div className="maintenance-list">
    {maintenance.map(item => {
      return <MaintenanceItem key={item.id} maintenance={item}/>
    })}
  </div>
}

export default MaintenanceList;