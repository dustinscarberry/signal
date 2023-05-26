import ContentBlock from '../ContentBlock';
import IncidentList from './IncidentList';

const View = ({title, incidents}) => {
  let viewTitle = 'Issues';
  if (title.trim() != '')
    viewTitle = title;

  return <ContentBlock>
    <div className="container-fluid">
      <div className="row">
        <div className="col-lg-12">
          <div className="incident-list-widget">
            <h2 className="widget-header">{viewTitle}</h2>
            <IncidentList incidents={incidents}/>
          </div>
        </div>
      </div>
    </div>
  </ContentBlock>
}

View.defaultProps = {
  title: '',
  incidents: []
}

export default View;