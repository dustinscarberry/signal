import ContentBlock from '../ContentBlock';
import Chart from './Chart';

const View = ({title, data, scale}) => {
  let formattedTitle = 'Services Uptime';
  if (title.trim() != '')
    formattedTitle = title;

  return <ContentBlock>
    <div className="container-fluid">
      <h2 className="widget-header">{formattedTitle}</h2>
      <div className="row">
        <div className="col-lg-12">
          <div className="service-uptime-chart-widget">
            <Chart
              data={data}
              scale={scale}
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