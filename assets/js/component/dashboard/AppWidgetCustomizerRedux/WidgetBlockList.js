import { HTML5Backend } from 'react-dnd-html5-backend';
import { DndProvider } from 'react-dnd';
import WidgetBlockContainer from './WidgetBlockContainer';

const WidgetBlockList = ({widgets, handleUpdateWidget, handleDeleteWidget, handleMoveWidget}) => {
  return <DndProvider backend={HTML5Backend}>
    <div>
      {widgets.map((widget, i) => {
        return <WidgetBlockContainer
          key={widget.id}
          widget={widget}
          widgets={widgets}
          index={i}
          handleUpdateWidget={handleUpdateWidget}
          handleDeleteWidget={handleDeleteWidget}
          handleMoveWidget={handleMoveWidget}
        />
      })}
    </div>
  </DndProvider>
}

export default WidgetBlockList;