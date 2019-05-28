import React from 'react';

const WidgetCreator = (props) =>
{
  return (
    <div className="widget-area" onClick={props.handleModalToggle}>
      <span className="widget-hint">Add Widget</span>
    </div>
  );
}

export default WidgetCreator;
