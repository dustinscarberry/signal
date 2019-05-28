import update from 'immutability-helper';

const initialState = {
  widgets: undefined
};

function rootReducer(state = initialState, action)
{
  if (action.type == 'WIDGETS_INITIALIZE')
  {
    return Object.assign({}, state, {
      widgets: action.payload
    });
  }
  else if (action.type == 'WIDGET_UPDATE')
  {
    let newState = Object.assign({}, state);
    newState.widgets[action.index] = action.widget;
    return newState;
  }
  else if (action.type == 'WIDGET_DELETE')
  {
    const widgets = update(state.widgets, {$splice: [
      [action.index, 1]
    ]});

    return Object.assign({}, state, {
      widgets: widgets
    });
  }
  else if (action.type == 'WIDGET_CREATE')
  {
    return Object.assign({}, state, {
      widgets: state.widgets.concat(action.widget)
    });
  }
  else if (action.type == 'WIDGET_MOVE')
  {
    const movingItem = state.widgets[action.fromIndex];

    const reorderedWidgets = update(state.widgets, {$splice: [
      [action.fromIndex, 1],
      [action.toIndex, 0, movingItem]
    ]});

    return Object.assign({}, state, {
      widgets: reorderedWidgets
    });
  }
  else if (action.type == 'WIDGETS_ORDER')
  {
    return Object.assign({}, state, {
      widgets: state.widgets.map((widget, index) => {
        widget.sortorder = index;
        return widget;
      })
    });
  }

  return state;
}

export default rootReducer;
