import ContentBlock from '../ContentBlock';
import MaintenanceList from './MaintenanceList';

const View = (props) => {
  let title = 'Maintenance';
  if (props.title.trim() != '')
    title = props.title;

  return <ContentBlock>
    <div className="container-fluid">
      <div className="row">
        <div className="col-lg-12">
          <div className="maintenance-list-widget">
            <h2 className="widget-header">{title}</h2>
            <MaintenanceList maintenance={props.maintenance}/>
          </div>
        </div>
      </div>
    </div>
  </ContentBlock>
}

View.defaultProps = {
  title: ''
};

export default View;
