import React from 'react';
import ContentBlock from '../ContentBlock';
import ResponsiveIframe from '../../shared/ResponsiveIframe';

const View = ({sourceURL}) =>
{
  return (
    <ContentBlock>
      <div className="container-fluid">
        <div className="row">
          <div className="col-lg-12">
            <ResponsiveIframe src={sourceURL}/>      
          </div>
        </div>
      </div>
    </ContentBlock>
  );
}

export default View;
