import React from 'react';
import classnames from 'classnames';
import {
  dispatchUpdateWidget,
  dispatchDeleteWidget,
  dispatchMoveWidget,
  dispatchOrderWidgets
} from './redux/actions';
import { connect } from 'react-redux';
import axios from 'axios';
import autoBind from 'react-autobind';
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

const mapStateToProps = (state) => {
  return {widgets: state.widgets};
};

const mapDispatchToProps = (dispatch) => {
  return {
    dispatchUpdateWidget: (index, widget) => dispatch(dispatchUpdateWidget(index, widget)),
    dispatchDeleteWidget: (index) => dispatch(dispatchDeleteWidget(index)),
    dispatchMoveWidget: (fromIndex, toIndex) => dispatch(dispatchMoveWidget(fromIndex, toIndex)),
    dispatchOrderWidgets: () => dispatch(dispatchOrderWidgets())
  };
};

class WidgetBlockContainer extends React.Component
{
  constructor(props)
  {
    super(props);

    this.state = {
      isOpen: props.widget.isNew ? true : false,
      isSaved: true
    };

    autoBind(this);
  }

  toggleOpenClose()
  {
    this.setState({isOpen: !this.state.isOpen});
  }

  toggleIsSaved(isSaved)
  {
    this.setState({isSaved});
  }

  createWidgetInDB(widget)
  {
    return axios.post(
      '/api/v1/widgets',
      {
        type: widget.type,
        sortorder: widget.sortorder,
        attributes: widget.attributes
      }
    );
  }

  updateWidgetInDB(widget)
  {
    return axios.patch(
      '/api/v1/widgets/' + widget.id,
      {
        type: widget.type,
        sortorder: widget.sortorder,
        attributes: widget.attributes
      }
    );
  }

  deleteWidgetInDB(widgetID)
  {
    return axios.delete(
      '/api/v1/widgets/' + widgetID
    );
  }

  updateWidgetOrderInDB(widgetIDs)
  {
    return axios.patch(
      '/api/v1/widgetsorder',
      {widgetIDs: widgetIDs}
    );
  }

  updateAttributes(attributes)
  {
    if (attributes) {
      let { widget } = this.props;
      widget.attributes = Object.assign(widget.attributes, attributes);
      this.props.dispatchUpdateWidget(this.props.index, widget);
    }
  }

  async saveWidget()
  {
    let { widget } = this.props;

    //save widget
    if (widget.id !== undefined) {
      const rsp = await this.updateWidgetInDB(widget);

      if (rsp.status == 200 && !rsp.data.error)
        this.toggleIsSaved(true);
    }
    //add widget
    else {
      const rsp = await this.createWidgetInDB(widget);

      if (rsp.status == 200 && !rsp.data.error && rsp.data.data.id !== undefined)
      {
        widget.id = rsp.data.data.id;
        this.props.dispatchUpdateWidget(this.props.index, widget);
        this.toggleIsSaved(true);
      }
    }

    return false;
  }

  async deleteWidget()
  {
    const { widget, index } = this.props;

    if (widget.id !== undefined)
    {
      const rsp = await this.deleteWidgetInDB(widget.id)

      if (rsp.status == 200 && !rsp.data.error)
        this.props.dispatchDeleteWidget(index);
    }
    else
      this.props.dispatchDeleteWidget(index);
  }

  //change table row order
  async moveWidget(dragIndex, hoverIndex)
  {
    if (dragIndex >= 0 && hoverIndex >= 0)
    {
      this.props.dispatchMoveWidget(dragIndex, hoverIndex);
      this.props.dispatchOrderWidgets();
    }
  }

  async saveWidgetsOrder()
  {
    const widgetIDs = this.props.widgets.map(widget => widget.id);
  }

  getBlockTitle()
  {
    const { widget } = this.props;
    let title = undefined;

    if (widget.type)
      title = WIDGET_BLOCK_ATTRIBUTES[widget.type].title;

    if (!title)
      title = 'Widget Block';

    return title;
  }

  getBlockType()
  {
    const { widget } = this.props;

    if (widget.type == WIDGET_BLOCK_TYPE.VIDEO_EMBED)
      return <VideoEmbedWidgetBlock
        toggleIsSaved={this.toggleIsSaved}
        updateAttributes={this.updateAttributes}
        {...this.state}
        {...this.props}
      />;
    else if (widget.type == WIDGET_BLOCK_TYPE.SERVICES_LIST)
      return <ServicesListWidgetBlock
        toggleIsSaved={this.toggleIsSaved}
        updateAttributes={this.updateAttributes}
        {...this.state}
        {...this.props}
      />;
    else if (widget.type == WIDGET_BLOCK_TYPE.INCIDENTS_LIST)
      return <IncidentsListWidgetBlock
        toggleIsSaved={this.toggleIsSaved}
        updateAttributes={this.updateAttributes}
        {...this.state}
        {...this.props}
      />;
    else if (widget.type == WIDGET_BLOCK_TYPE.MAINTENANCE_LIST)
      return <MaintenanceListWidgetBlock
        toggleIsSaved={this.toggleIsSaved}
        updateAttributes={this.updateAttributes}
        {...this.state}
        {...this.props}
      />;
    else if (widget.type == WIDGET_BLOCK_TYPE.SERVICE_STATUS_OVERVIEW)
      return <ServiceStatusOverviewWidgetBlock
        toggleIsSaved={this.toggleIsSaved}
        updateAttributes={this.updateAttributes}
        {...this.state}
        {...this.props}
      />;
    else if (widget.type == WIDGET_BLOCK_TYPE.METRICS_OVERVIEW)
      return <MetricsOverviewWidgetBlock
        toggleIsSaved={this.toggleIsSaved}
        updateAttributes={this.updateAttributes}
        {...this.state}
        {...this.props}
      />;
    else if (widget.type == WIDGET_BLOCK_TYPE.SERVICE_UPTIME_CHART)
      return <ServiceUptimeChartWidgetBlock
        toggleIsSaved={this.toggleIsSaved}
        updateAttributes={this.updateAttributes}
        {...this.state}
        {...this.props}
      />;
    else if (widget.type == WIDGET_BLOCK_TYPE.CUSTOM_METRIC_CHART)
      return <CustomMetricChartWidgetBlock
        toggleIsSaved={this.toggleIsSaved}
        updateAttributes={this.updateAttributes}
        {...this.state}
        {...this.props}
      />;
    else
      return null;
  }

  render()
  {
    return (
      <WidgetBlock
        title={this.getBlockTitle()}
        saveWidget={this.saveWidget}
        deleteWidget={this.deleteWidget}
        toggleOpenClose={this.toggleOpenClose}
        moveWidget={this.moveWidget}
        saveWidgetsOrder={this.saveWidgetsOrder}
        {...this.props}
        {...this.state}
      >
        {this.getBlockType()}
      </WidgetBlock>
    );
  }
}

export default connect(mapStateToProps, mapDispatchToProps)(WidgetBlockContainer);
