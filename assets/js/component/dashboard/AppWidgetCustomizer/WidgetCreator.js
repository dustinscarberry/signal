const WidgetCreator = ({handleModalToggle}) => {
  return <div className="widget-area" onClick={handleModalToggle}>
    <span className="widget-hint">Add Widget</span>
  </div>
}

export default WidgetCreator;