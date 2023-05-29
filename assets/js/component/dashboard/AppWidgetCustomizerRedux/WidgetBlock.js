import { useRef } from 'react';
import { useDrag, useDrop } from 'react-dnd';
import classnames from 'classnames';
import WidgetBlockActions from './WidgetBlockActions';

const WidgetBlock = (props) => {

  const ref = useRef(null);

  const [dragCollected, dragRef] = useDrag({
    type: 'widgetblock',
    item: {
      index: props.index
    },
    collect: (monitor) => ({
      isDragging: monitor.isDragging()
    }),
  });

  const [dropCollected, dropRef] = useDrop({
    accept: 'widgetblock',
    drop: (item, monitor) => {
      props.saveWidgetsOrder();
    },
    hover: (item, monitor) => {
      if (!item)
        return null;

      const dragIndex = monitor.getItem().index;
      const hoverIndex = props.index;

      // don't replace item with itself
      if (dragIndex === hoverIndex)
        return;

      // determine rectangle on screen
      const hoverBoundingRect = ref.current?.getBoundingClientRect();
  
      // get vertical middle
      const hoverMiddleY = (hoverBoundingRect.bottom - hoverBoundingRect.top) / 2;
  
      // get mouse position
      const clientOffset = monitor.getClientOffset();
  
      // get pixels to top
      const hoverClientY = clientOffset.y - hoverBoundingRect.top
  
      // Only perform the move when the mouse has crossed half of the items height
      // When dragging downwards, only move when the cursor is below 50%
      // When dragging upwards, only move when the cursor is above 50%
  
      // Dragging downwards
      if (dragIndex < hoverIndex && hoverClientY < hoverMiddleY)
        return;
  
      // Dragging upwards
      if (dragIndex > hoverIndex && hoverClientY > hoverMiddleY)
        return;
  
      // Time to actually perform the action
      props.moveWidget(dragIndex, hoverIndex);
  
      // change monitor index for performance reasons
      monitor.getItem().index = hoverIndex;
    }
  });

  dragRef(dropRef(ref));

  const { title, isOpen, children, toggleOpenClose } = props;

  return <div className={classnames('widget-box', {'is-open': isOpen, 'is-dragging': dragCollected.isDragging})}>
    <h3 className="widget-header" onClick={toggleOpenClose} ref={ref}>{title}</h3>
    <div className="widget-settings">
      <div className="widget-settings-inner">
        {children}
        <WidgetBlockActions {...props}/>
      </div>
    </div>
  </div> 
}

WidgetBlock.defaultProps = {
  title: 'Widget Block'
}

export default WidgetBlock;