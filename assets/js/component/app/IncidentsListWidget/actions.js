import React from 'react';

export const getFormattedDateTime = (timestamp) =>
{
  if (!timestamp)
    return undefined;

  const date = new Date(timestamp * 1000);
  return date.toLocaleString(
    'en-US',
    {
      year: 'numeric',
      month: 'numeric',
      day: 'numeric',
      hour: 'numeric',
      minute: 'numeric',
      hour12: true,
      timeZoneName: 'short'
    }
  );
}

export const getStatusIconClasses = (iconType) =>
{
  const classes = ['status-icon'];
  classes.push('status-icon-' + iconType);
  return classes;
}

export const isValidResponse = (rsp) =>
{
  return rsp && rsp.status == 200 && !rsp.data.error;
}

//convert new lines to html breaks
export const nl2br = (text) =>
{
  return text.split('\n').map((item, key) => {
    return <span key={key}>{item}<br/></span>;
  })
}
