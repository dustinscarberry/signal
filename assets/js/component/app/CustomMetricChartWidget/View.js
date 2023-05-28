import ContentBlock from '../ContentBlock';
import Chart from './Chart';

const View = ({title, data, yLegend, scale, scaleStart, scaleEnd}) => {
  let formattedTitle = 'Custom Metric';
  if (title.trim() != '')
    formattedTitle = title;

  return <ContentBlock>
    <div className="container-fluid">
      <h2 className="widget-header">{formattedTitle}</h2>
      <div className="row">
        <div className="col-lg-12">
          <div className="custom-metric-chart-widget">
            <Chart
              data={data}
              yLegend={yLegend}
              scale={scale}
              scaleStart={scaleStart}
              scaleEnd={scaleEnd}
            />
          </div>
        </div>
      </div>
    </div>
  </ContentBlock>
}

export default View;