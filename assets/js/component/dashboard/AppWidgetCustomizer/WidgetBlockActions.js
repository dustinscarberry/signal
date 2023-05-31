const WidgetBlockActions = ({handleRemoveWidget, toggleOpenClose, handleSaveWidget, isSaved}) => {
  return <div className="widget-box-actions clearfix">
    <div className="widget-box-actions-left">
      <button className="text-btn" onClick={handleRemoveWidget}>Delete</button>
      <span>|</span>
      <button className="text-btn" onClick={toggleOpenClose}>Done</button>
    </div>
    <div className="widget-box-actions-right">
      <button className="btn btn-primary" onClick={handleSaveWidget} disabled={isSaved}>Save</button>
    </div>
  </div>
}

export default WidgetBlockActions;