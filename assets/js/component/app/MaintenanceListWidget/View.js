import ContentBlock from '../ContentBlock';
import MaintenanceList from './MaintenanceList';

const View = ({title, maintenance}) => {
  let formattedTitle = 'Maintenance';
  if (title.trim() != '')
    formattedTitle = title;

  return <ContentBlock>
    <div className="container-fluid">
      <div className="row">
        <div className="col-lg-12">
          <div className="maintenance-list-widget">
            <h2 className="widget-header">{formattedTitle}</h2>
            <MaintenanceList maintenance={maintenance}/>
          </div>
        </div>
      </div>
    </div>
  </ContentBlock>
}

View.defaultProps = {
  title: '',
  maintenance: undefined
};

export default View;