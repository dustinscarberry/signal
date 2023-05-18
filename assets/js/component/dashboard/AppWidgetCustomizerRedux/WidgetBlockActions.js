const WidgetBlockActions = ({deleteWidget, toggleOpenClose, saveWidget, isSaved}) => {
  return <div className="widget-box-actions clearfix">
    <div className="widget-box-actions-left">
      <button className="text-btn" onClick={deleteWidget}>Delete</button>
      <span>|</span>
      <button className="text-btn" onClick={toggleOpenClose}>Done</button>
    </div>
    <div className="widget-box-actions-right">
      <button className="btn btn-primary" onClick={saveWidget} disabled={isSaved}>Save</button>
    </div>
  </div>
}

export default WidgetBlockActions;
