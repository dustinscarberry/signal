import ContentBlock from '../ContentBlock';
import classnames from 'classnames';

const View = (props) => {
  return <ContentBlock>
    <div className="container-fluid">
      <div className="row">
        <div className="col-lg-12">
          <span className={classnames('status-overview', props.statusClasses)}>{props.message}</span>
        </div>
      </div>
    </div>
  </ContentBlock>
}

View.defaultProps = {};

export default View;
