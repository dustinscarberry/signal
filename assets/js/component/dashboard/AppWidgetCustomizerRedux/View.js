import React from 'react';
import autobind from 'autobind-decorator';
import WidgetCreator from './WidgetCreator';
import WidgetBlockList from './WidgetBlockList';
import WidgetSelector from './WidgetSelector';
import Loader from '../../shared/Loader';
import { WIDGET_BLOCK_DATA } from '../constants';
import axios from 'axios';
import { connect } from 'react-redux';
import { dispatchInitializeWidgets, dispatchCreateWidget } from './redux/actions';

function mapStateToProps(state) {
  return {
    widgets: state.widgets
  };
}

function mapDispatchToProps(dispatch) {
  return {
    dispatchInitializeWidgets: (widgets) => dispatch(dispatchInitializeWidgets(widgets)),
    dispatchCreateWidget: (widget) => dispatch(dispatchCreateWidget(widget))
  };
}

class View extends React.Component
{
  constructor(props)
  {
    super(props);

    this.state = {
      isOpenWidgetSelector: false
    };
  }

  componentDidMount()
  {
    this.loadWidgets();
  }

  async loadWidgets()
  {
    const rsp = await axios.get('/api/v1/widgets');

    if (rsp.status == 200 && !rsp.data.error)
      this.props.dispatchInitializeWidgets(rsp.data.data);
  }

  @autobind
  handleModalToggle()
  {
    this.setState({isOpenWidgetSelector: !this.state.isOpenWidgetSelector});
  }

  @autobind
  async addWidget()
  {
    this.setState({isOpenWidgetSelector: !this.state.isOpenWidgetSelector});
  }

  @autobind
  handleAddWidgetType(type)
  {
    const newWidget = WIDGET_BLOCK_DATA[type];
    newWidget.sortorder = this.props.widgets.length + 1;
    newWidget.isNew = true;
    this.props.dispatchCreateWidget(newWidget);
    this.setState({isOpenWidgetSelector: false});
  }

  render()
  {
    if (!this.props.widgets)
      return <Loader/>;

    return (
      <div>
        <WidgetBlockList/>
        <WidgetCreator
          handleModalToggle={this.handleModalToggle}
        />
        <WidgetSelector
          isOpenWidgetSelector={this.state.isOpenWidgetSelector}
          handleAddWidgetType={this.handleAddWidgetType}
          handleModalToggle={this.handleModalToggle}
        />
      </div>
    );
  }
}

export default connect(mapStateToProps, mapDispatchToProps)(View);
