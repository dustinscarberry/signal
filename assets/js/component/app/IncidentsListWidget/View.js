import React from 'react';
import ContentBlock from '../ContentBlock';
import IncidentList from './IncidentList';

const View = (props) =>
{
  let title = 'Issues';
  if (props.title.trim() != '')
    title = props.title;

  return (
    <ContentBlock>
      <div className="container-fluid">
        <div className="row">
          <div className="col-lg-12">
            <div className="incident-list-widget">
              <h2 className="widget-header">{title}</h2>
              <IncidentList incidents={props.incidents}/>
            </div>
          </div>
        </div>
      </div>
    </ContentBlock>
  );
}

View.defaultProps = {
  title: ''
};

export default View;
