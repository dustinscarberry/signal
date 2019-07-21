import React from 'react';

import ContentBlock from '../ContentBlock';
import Link from './Link';

const View = (props) =>
{
  //set links based on configuration
  const linkNodes = [];

  if (props.showPastMaintenance)
    linkNodes.push(<Link href='/pastmaintenance' title='Past Maintenance'/>);

  if (props.showFutureMaintenance)
    linkNodes.push(<Link href='/scheduledmaintenance' title='Scheduled Maintenance'/>);

  if (props.showPastIncidents)
    linkNodes.push(<Link href='/pastincidents' title='Past Incidents'/>);

  return (
    <ContentBlock>
      <div className="container-fluid">
        <div className="row">
          <div className="col-lg-12">
            <div className="past-future-links-widget">
              {linkNodes}
            </div>
          </div>
        </div>
      </div>
    </ContentBlock>
  );
}

View.defaultProps = {
  showPastMaintenance: false,
  showFutureMaintenance: false,
  showPastIncidents: false
};

export default View;
