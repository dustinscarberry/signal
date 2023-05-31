import { useState, useEffect } from 'react';
import cloneDeep from 'lodash/cloneDeep';
import { isOk } from '../../../logic/utils';
import { fetchWidgets } from './logic';
import { WIDGET_BLOCK_DATA } from '../constants';
import View from './View';

const AppWidgetCustomizer = () => {
  const [widgets, setWidgets] = useState();
  const [isOpenWidgetSelector, setIsOpenWidgetSelector] = useState(false);

  useEffect(() => {
    loadWidgets();
  }, []);

  const loadWidgets = async () => {
    const rsp = await fetchWidgets();

    if (isOk(rsp))
      setWidgets(rsp.data.data);
  }

  const handleModalToggle = () => {
    setIsOpenWidgetSelector(!isOpenWidgetSelector);
  }

  const handleAddWidgetType = (type) => {
    const newWidget = WIDGET_BLOCK_DATA[type];
    newWidget.sortorder = widgets.length + 1;
    newWidget.isNew = true;

    const widgetsClone = cloneDeep(widgets);
    widgetsClone.push(newWidget);
    setWidgets(widgetsClone);

    setIsOpenWidgetSelector(false);
  }
  
  const handleUpdateWidget = (index, widget) => {
    const widgetsClone = cloneDeep(widgets);
    widget.isNew = false;
    widgetsClone[index] = widget;
    setWidgets(widgetsClone);
  }

  const handleDeleteWidget = (index) => {
    const widgetsClone = cloneDeep(widgets);
    widgetsClone.splice(index, 1);
    setWidgets(widgetsClone);
  }

  const handleMoveWidget = (fromIndex, toIndex) => {
    if (fromIndex >= 0 && toIndex >= 0) {
      const widgetsClone = cloneDeep(widgets);
      const movingItem = widgetsClone[fromIndex];
      widgetsClone.splice(fromIndex, 1);
      widgetsClone.splice(toIndex, 0, movingItem);

      // reset the internal ordering of the widgets
      const reorderedWidgets = widgetsClone.map((widget, index) => {
        widget.sortorder = index;
        return widget;
      });
  
      setWidgets(widgetsClone);
    }
  }
  
  return <View
    widgets={widgets}
    isOpenWidgetSelector={isOpenWidgetSelector}
    handleModalToggle={handleModalToggle}
    handleAddWidgetType={handleAddWidgetType}
    handleUpdateWidget={handleUpdateWidget}
    handleDeleteWidget={handleDeleteWidget}
    handleMoveWidget={handleMoveWidget}
  />
}

export default AppWidgetCustomizer;