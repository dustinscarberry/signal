import React from 'react';
import Iframe from './Iframe';

const ResponsiveIframe = (props) =>
{
  return (
    <div className="embed-responsive embed-responsive-16by9">
      <Iframe src={props.src}/>
    </div>
  );
}

export default ResponsiveIframe;
