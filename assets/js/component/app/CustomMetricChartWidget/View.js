import ContentBlock from '../ContentBlock';
import Chart from './Chart';

const View = (props) => {
  let title = 'Custom Metric';
  if (props.title.trim() != '')
    title = props.title;

  return <ContentBlock>
    <div className="container-fluid">
      <h2 className="widget-header">{title}</h2>
      <div className="row">
        <div className="col-lg-12">
          <div className="custom-metric-chart-widget">
            <Chart
              data={props.data}
              yLegend={props.yLegend}
              scale={props.scale}
              scaleStart={props.scaleStart}
              scaleEnd={props.scaleEnd}
            />
          </div>
        </div>
      </div>
    </div>
  </ContentBlock>
}

View.defaultProps = {
  title: ''
}

export default View;
