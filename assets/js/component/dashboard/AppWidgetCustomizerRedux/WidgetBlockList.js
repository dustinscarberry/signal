import { HTML5Backend } from 'react-dnd-html5-backend';
import { DndProvider } from 'react-dnd';
import { connect } from 'react-redux';
import WidgetBlockContainer from './WidgetBlockContainer';

const mapStateToProps = (state) => {
  return {widgets: state.widgets};
}

const WidgetBlockList = (props) => {
  const widgetNodes = props.widgets.map((widget, i) =>
  {
    return <WidgetBlockContainer
      widget={widget}
      key={widget.id}
      index={i}
    />;
  });

  return <DndProvider backend={HTML5Backend}>
    <div>
      {widgetNodes}
    </div>
  </DndProvider>
}

export default connect(mapStateToProps)(WidgetBlockList);
