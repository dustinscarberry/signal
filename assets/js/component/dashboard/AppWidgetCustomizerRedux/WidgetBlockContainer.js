import { useState } from 'react';
import { isOk } from '../../../logic/utils';
import { createWidget, updateWidget, deleteWidget, updateWidgetOrder } from './logic';
import { WIDGET_BLOCK_TYPE, WIDGET_BLOCK_ATTRIBUTES } from '../constants';
import WidgetBlock from './WidgetBlock';
import VideoEmbedWidgetBlock from './blocks/VideoEmbedWidgetBlock';
import ServicesListWidgetBlock from './blocks/ServicesListWidgetBlock';
import IncidentsListWidgetBlock from './blocks/IncidentsListWidgetBlock';
import MaintenanceListWidgetBlock from './blocks/MaintenanceListWidgetBlock';
import ServiceStatusOverviewWidgetBlock from './blocks/ServiceStatusOverviewWidgetBlock';
import MetricsOverviewWidgetBlock from './blocks/MetricsOverviewWidgetBlock';
import ServiceUptimeChartWidgetBlock from './blocks/ServiceUptimeChartWidgetBlock';
import CustomMetricChartWidgetBlock from './blocks/CustomMetricChartWidgetBlock';
import PastFutureLinksWidgetBlock from './blocks/PastFutureLinksWidgetBlock';

const WidgetBlockContainer = ({widget, widgets, index, handleUpdateWidget, handleDeleteWidget, handleMoveWidget}) => {
  const [isOpen, setIsOpen] = useState(widget.isNew ? true : false);
  const [isSaved, setIsSaved] = useState(true);

  const toggleOpenClose = () => {
    setIsOpen(!isOpen);
  }

  const toggleIsSaved = (isSaved) => {
    setIsSaved(isSaved);
  }

  const handleUpdateAttributes = (attributes) => {
    if (attributes) {
      widget.attributes = Object.assign(widget.attributes, attributes);
      handleUpdateWidget(index, widget);
    }
  }

  const handleSaveWidget = async () => {
    // save widget
    if (widget.id !== undefined) {
      const rsp = await updateWidget(widget);

      if (isOk(rsp))
        toggleIsSaved(true);
    }
    // add widget
    else {
      const rsp = await createWidget(widget);

      if (isOk(rsp) && rsp.data.data.id !== undefined) {
        widget.id = rsp.data.data.id;
        handleUpdateWidget(index, widget);
        toggleIsSaved(true);
      }
    }

    return false;
  }

  const handleRemoveWidget = async () => {
    if (widget.id !== undefined) {
      const rsp = await deleteWidget(widget.id);

      if (isOk(rsp))
        handleDeleteWidget(index);
    } else
      handleDeleteWidget(index);
  }

  const handleSaveWidgetsOrder = async () => {
    const widgetIDs = widgets.map(widget => widget.id);
    updateWidgetOrder(widgetIDs);
  }

  const getBlockTitle = () => {
    let title;

    if (widget.type)
      title = WIDGET_BLOCK_ATTRIBUTES[widget.type].title;

    if (!title)
      title = 'Widget Block';

    return title;
  }

  const getBlockType = () => {
    const blockProps = {
      toggleIsSaved: toggleIsSaved,
      handleUpdateAttributes: handleUpdateAttributes,
      isOpen: isOpen,
      isSaved: isSaved,
      widget: widget,
      index: index
    };

    if (widget.type == WIDGET_BLOCK_TYPE.VIDEO_EMBED)
      return <VideoEmbedWidgetBlock
        {...blockProps}
      />
    else if (widget.type == WIDGET_BLOCK_TYPE.SERVICES_LIST)
      return <ServicesListWidgetBlock
        {...blockProps}
      />
    else if (widget.type == WIDGET_BLOCK_TYPE.INCIDENTS_LIST)
      return <IncidentsListWidgetBlock
        {...blockProps}
      />
    else if (widget.type == WIDGET_BLOCK_TYPE.MAINTENANCE_LIST)
      return <MaintenanceListWidgetBlock
        {...blockProps}
      />
    else if (widget.type == WIDGET_BLOCK_TYPE.SERVICE_STATUS_OVERVIEW)
      return <ServiceStatusOverviewWidgetBlock
        {...blockProps}
      />
    else if (widget.type == WIDGET_BLOCK_TYPE.METRICS_OVERVIEW)
      return <MetricsOverviewWidgetBlock
        {...blockProps}
      />
    else if (widget.type == WIDGET_BLOCK_TYPE.SERVICE_UPTIME_CHART)
      return <ServiceUptimeChartWidgetBlock
        {...blockProps}
      />
    else if (widget.type == WIDGET_BLOCK_TYPE.CUSTOM_METRIC_CHART)
      return <CustomMetricChartWidgetBlock
        {...blockProps}
      />
    else if (widget.type == WIDGET_BLOCK_TYPE.PAST_FUTURE_LINKS)
      return <PastFutureLinksWidgetBlock
        {...blockProps}
      />
    else
      return null;
  }

  return <WidgetBlock
    title={getBlockTitle()}
    handleSaveWidget={handleSaveWidget}
    handleRemoveWidget={handleRemoveWidget}
    toggleOpenClose={toggleOpenClose}
    handleMoveWidget={handleMoveWidget}
    handleSaveWidgetsOrder={handleSaveWidgetsOrder}
    isOpen={isOpen}
    isSaved={isSaved}
    index={index}
  >
    {getBlockType()}
  </WidgetBlock>
  
}

export default WidgetBlockContainer;