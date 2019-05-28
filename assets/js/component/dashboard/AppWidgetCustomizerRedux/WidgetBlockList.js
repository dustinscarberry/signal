import React from 'react';
import HTML5Backend from 'react-dnd-html5-backend';
import { DragDropContext } from 'react-dnd';
import { connect } from 'react-redux';
import WidgetBlockContainer from './WidgetBlockContainer';

const mapStateToProps = (state) => {
  return {widgets: state.widgets};
}

const WidgetBlockList = (props) =>
{
  const widgetNodes = props.widgets.map((widget, i) =>
  {
    return <WidgetBlockContainer
      widget={widget}
      key={widget.id}
      index={i}
    />;
  });

  return (
    <div>
      {widgetNodes}
    </div>
  );
}

export default connect(mapStateToProps)(DragDropContext(HTML5Backend)(WidgetBlockList));
