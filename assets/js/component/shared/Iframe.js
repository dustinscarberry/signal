import React from 'react';

const Iframe = (props) =>
{
  return <iframe src={props.src} allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowFullScreen></iframe>;
}

export default Iframe;
