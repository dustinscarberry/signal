import React from 'react';
import { DragSource, DropTarget } from 'react-dnd';
import { findDOMNode } from 'react-dom';
import classnames from 'classnames';
import WidgetBlockActions from './WidgetBlockActions';

const itemType = 'widgetblock';

const rowSource =
{
  beginDrag(props)
  {
    return {
      index: props.index
    }
  }
};

const rowSourceCollect = (connect, monitor) =>
{
  return {
    connectDragSource: connect.dragSource(),
    isDragging: monitor.isDragging()
  };
}

const rowTarget =
{
  drop(props, monitor, component)
  {
    props.saveWidgetsOrder();
    return undefined;
  },
  hover(props, monitor, component)
  {
    if (!component)
			return null;

		const dragIndex = monitor.getItem().index;
    const hoverIndex = props.index;

		//don't replace item with itself
		if (dragIndex === hoverIndex)
			return;

    //determine rectangle on screen
    const hoverBoundingRect = (findDOMNode(component)).getBoundingClientRect();

    //get vertical middle
    const hoverMiddleY = (hoverBoundingRect.bottom - hoverBoundingRect.top) / 2;

    //get mouse position
    const clientOffset = monitor.getClientOffset();

		//get pixels to top
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

		//change monitor index for performance reasons
		monitor.getItem().index = hoverIndex;
  }
};

const rowTargetCollect = (connect, monitor) =>
{
  return {
    connectDropTarget: connect.dropTarget()
  };
}

class WidgetBlock extends React.Component
{
  constructor(props)
  {
    super(props);
  }

  render()
  {
    const boxClasses = ['widget-box'];

    if (this.props.isOpen)
      boxClasses.push('is-open');

    if (this.props.isDragging)
      boxClasses.push('is-dragging');

    return this.props.connectDragSource &&
      this.props.connectDropTarget &&
      this.props.connectDropTarget(
        <div className={classnames(boxClasses)}>
          {this.props.connectDragSource(<h3 className="widget-header" onClick={this.props.toggleOpenClose}>{this.props.title}</h3>)}
          <div className="widget-settings">
            <div className="widget-settings-inner">
              {this.props.children}
              <WidgetBlockActions {...this.props}/>
            </div>
          </div>
        </div>
      );
  }
}

WidgetBlock.defaultProps = {
  title: 'Widget Block'
};

export default DragSource(itemType, rowSource, rowSourceCollect, {arePropsEqual: (props, otherProps) => {return false;}})(
  DropTarget(itemType, rowTarget, rowTargetCollect, {arePropsEqual: (props, otherProps) => {return false;}})(WidgetBlock)
);
