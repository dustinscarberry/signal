export function dispatchInitializeWidgets(payload) {
  return {
    type: 'WIDGETS_INITIALIZE',
    payload
  };
};

export function dispatchUpdateWidget(index, widget) {
  return {
    type: 'WIDGET_UPDATE',
    index,
    widget
  };
}

export function dispatchDeleteWidget(index) {
  return {
    type: 'WIDGET_DELETE',
    index
  };
}

export function dispatchCreateWidget(widget) {
  return {
    type: 'WIDGET_CREATE',
    widget
  };
}

export function dispatchMoveWidget(fromIndex, toIndex) {
  return {
    type: 'WIDGET_MOVE',
    fromIndex,
    toIndex
  }
}

export function dispatchOrderWidgets() {
  return {
    type: 'WIDGETS_ORDER'
  }
}
