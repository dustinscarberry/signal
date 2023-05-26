import ContentBlock from '../ContentBlock';
import Chart from './Chart';

const View = (props) => {
  let title = 'Services Uptime';
  if (props.title.trim() != '')
    title = props.title;

  return <ContentBlock>
    <div className="container-fluid">
      <h2 className="widget-header">{title}</h2>
      <div className="row">
        <div className="col-lg-12">
          <div className="service-uptime-chart-widget">
            <Chart
              data={props.data}
              scale={props.scale}
            />
          </div>
        </div>
      </div>
    </div>
  </ContentBlock>
}

View.defaultProps = {
  title: '',
  scale: 'day',
  data: undefined
}

export default View;