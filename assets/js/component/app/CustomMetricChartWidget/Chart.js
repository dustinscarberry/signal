import { Line } from 'react-chartjs-2';
import 'chartjs-adapter-luxon';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  TimeScale,
  Filler,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
} from 'chart.js';

ChartJS.register(
  CategoryScale,
  LinearScale,
  TimeScale,
  Filler,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
);

const Chart = (props) => {
  let dataFormat;
  if (props.scale == 'minute')
    dataFormat = 'yyyy-LL-dd HH:mm';
  else if (props.scale == 'hour')
    dataFormat = 'yyyy-LL-dd HH';
  else if (props.scale == 'day')
    dataFormat = 'yyyy-LL-dd';

  return <Line
    options={{
      responsive: true,
      layout: {
        padding: 20
      },
      plugins: {
        legend: {
          position: 'top',
        },
        tooltip: {
          interaction: {
            mode: 'nearest',
            intersect: false
          },
          callbacks: {
            title: (tooltipItems) => {
              return tooltipItems[0].dataset.label;
            },
            label: (tooltipItem) => {
              return tooltipItem.raw;
            }
          }
        }
      },
      scales: {
        x: {
          type: 'time',
          ticks: {
            autoSkip: true,
            maxTicksLimit: 15
          },
          time: {
            unit: props.scale,
            parser: dataFormat
          }
        },
        y: {
          type: 'linear',
          min: 0,
          max: 110,
          ticks: {
            stepSize: 10
          },
          title: {
            text: props.yLegend,
            display: true
          }
        }
      }
    }}
    data={{
      labels: props.data[0].data.map(x => x.x),
      datasets: props.data.map(i => {
        return {
          label: i.id,
          data: i.data.map(x => x.y),
          borderColor: 'rgb(255, 99, 132)',
          backgroundColor: 'rgba(255, 99, 132, 0.5)',
          lineTension: 0.4,
          pointHitRadius: 6,
          pointRadius: 0,
          fill: 'origin'
        }
      })
    }}
  />
}

Chart.defaultProps = {
  data: [],
  yLegend: '',
  scale: 'day',
  scaleStart: 0,
  scaleEnd: 100
};

export default Chart;