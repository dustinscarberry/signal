import React from 'react';

const WidgetBlockActions = (props) =>
{
  return (
    <div className="widget-box-actions clearfix">
      <div className="widget-box-actions-left">
        <button className="text-btn" onClick={props.deleteWidget}>Delete</button>
        <span>|</span>
        <button className="text-btn" onClick={props.toggleOpenClose}>Done</button>
      </div>
      <div className="widget-box-actions-right">
        <button className="btn btn-primary" onClick={props.saveWidget} disabled={props.isSaved}>Save</button>
      </div>
    </div>
  );
}

export default WidgetBlockActions;
