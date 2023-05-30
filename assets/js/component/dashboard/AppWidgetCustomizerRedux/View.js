import WidgetCreator from './WidgetCreator';
import WidgetBlockList from './WidgetBlockList';
import WidgetSelector from './WidgetSelector';
import Loader from '../../shared/Loader';

const View = (props) => {
  if (!props.widgets)
    return <Loader/>

  return <div>
    <WidgetBlockList
      widgets={props.widgets}
      handleUpdateWidget={props.handleUpdateWidget}
      handleDeleteWidget={props.handleDeleteWidget}
      handleMoveWidget={props.handleMoveWidget}
    />
    <WidgetCreator
      handleModalToggle={props.handleModalToggle}
    />
    <WidgetSelector
      isOpenWidgetSelector={props.isOpenWidgetSelector}
      handleAddWidgetType={props.handleAddWidgetType}
      handleModalToggle={props.handleModalToggle}
    />
  </div>
}

export default View;