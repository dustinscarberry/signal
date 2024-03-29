import Modal from '../../shared/Modal';
import WidgetSelection from './WidgetSelection';
import { WIDGET_BLOCK_ATTRIBUTES } from '../constants';

const WidgetSelector = ({isOpenWidgetSelector, handleAddWidgetType, handleModalToggle}) => {
  const widgetNodes = Object.keys(WIDGET_BLOCK_ATTRIBUTES).map((key, val) => {
    return <WidgetSelection
      key={key}
      attributes={WIDGET_BLOCK_ATTRIBUTES[key]}
      type={key}
      handleAddWidgetType={handleAddWidgetType}
    />
  });

  return <Modal
    isOpen={isOpenWidgetSelector}
    handleModalToggle={handleModalToggle}
    title="Add Widget"
  >
    <div className="container-fluid widget-selector">
      <ul className="row">
        {widgetNodes}
      </ul>
    </div>
  </Modal>
}

export default WidgetSelector;