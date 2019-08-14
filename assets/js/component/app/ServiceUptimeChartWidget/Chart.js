import React from 'react';
import { ResponsiveLine } from '@nivo/line'
import {
  getChartScaleFormat,
  getChartLegendFormat
} from './actions';

const Chart = (props) =>
{
  let scaleFormat = getChartScaleFormat(props.scale);
  let legendFormat = getChartLegendFormat(props.scale);

  return (
    <ResponsiveLine
      data={props.data}
      margin={{
        top: 50,
        right: 50,
        bottom: 50,
        left: 75
      }}
      enableArea={true}
      curve='linear'
      xScale={{
        type: 'time',
        format: scaleFormat,
        precision: props.scale
      }}
      yScale={{
        type: 'linear',
        stacked: false,
        min: 0,
        max: 110
      }}
      axisBottom={{
          "orient": "bottom",
          "tickSize": 5,
          "tickPadding": 5,
          "tickRotation": 0,
          format: legendFormat
      }}
      axisLeft={{
          "orient": "left",
          "tickSize": 5,
          "tickPadding": 5,
          "tickRotation": 0,
          "legend": "Uptime %",
          "legendOffset": -40,
          "legendPosition": "middle"
      }}
      colors={{
          "scheme": "dark2"
      }}
      pointSize={10}
      pointColor={{
          from: 'color'
      }}
      pointBorderWidth={2}
      pointBorderColor={{
          "from": "color"
      }}
      enablePoints={false}
      enablePointLabel={false}
      pointLabel="y"
      pointLabelYOffset={-12}
      animate={true}
      motionStiffness={90}
      motionDamping={15}
      enableGridX={true}
      enableGridY={true}
      enableSlices="x"
    />
  );
}

Chart.defaultProps = {
  data: [],
  scale: 'day'
};

export default Chart;
