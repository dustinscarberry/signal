export const isValidResponse = (rsp) =>
{
  return rsp && rsp.status == 200 && !rsp.data.error;
}

export const getChartScaleFormat = (scale) =>
{
  if (scale == 'day')
    return '%Y-%m-%d';
  else if (scale == 'hour')
    return '%Y-%m-%d %H';
  else if (scale == 'minute')
    return '%Y-%m-%d %H:%M';
}

export const getChartLegendFormat = (scale) =>
{
  if (scale == 'day')
    return '%Y-%m-%d';
  else if (scale == 'hour')
    return '%I:00 %p';
  else if (scale == 'minute')
    return '%I:%M %p';
}
