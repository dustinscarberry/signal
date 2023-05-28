import { Tooltip } from 'react-tooltip';
import classnames from 'classnames';
import { getStatusBubbleClasses } from './logic';

const Service = ({service}) => {
  const statusClasses = getStatusBubbleClasses(service.statusType);

  return <div className="service-list-item">
    <span className="service-name">{service.name}</span>
    <i className="far fa-question-circle service-status-hint" data-tooltip-id="overlay-tooltip" data-tooltip-content={service.description}></i>
    <span className={classnames('service-status', statusClasses)} data-tooltip-id="overlay-tooltip" data-tooltip-content={service.status}></span>
    <Tooltip id="overlay-tooltip"/>
  </div>
}

export default Service;
