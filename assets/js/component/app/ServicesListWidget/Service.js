import ReactTooltip from 'react-tooltip';
import classnames from 'classnames';
import { getStatusBubbleClasses } from './actions';

const Service = (props) => {
  const statusClasses = getStatusBubbleClasses(props.service.statusType);

  return <div className="service-list-item">
    <span className="service-name">{props.service.name}</span>
    <i className="far fa-question-circle service-status-hint" data-tip={props.service.description}></i>
    <span className={classnames('service-status', statusClasses)} data-tip={props.service.status}></span>
    <ReactTooltip/>
  </div>
}

export default Service;
