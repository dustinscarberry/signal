import classnames from 'classnames';

const WidgetSelection = (props) => {
  return <li className="col-sm-3 widget-selector-item">
    <div className="widget-selector-item-inner" onClick={() => props.handleAddWidgetType(props.type)}>
      <i className={classnames('widget-selector-item-icon', props.attributes.iconClass)}></i>
      <span className="widget-selector-item-name">{props.attributes.title}{props.test}</span>
    </div>
  </li>
}

export default WidgetSelection;