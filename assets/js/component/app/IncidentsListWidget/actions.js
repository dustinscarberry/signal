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
      hour12: true
    }
  );
}

export const getStatusIconClasses = (iconType) =>
{
  const classes = ['status-icon'];

  if (iconType == 'error')
    classes.push('status-icon-error');
  else if (iconType == 'issues')
    classes.push('status-icon-issue');
  else if (iconType == 'ok')
    classes.push('status-icon-ok');

  return classes;
}

export const isValidResponse = (rsp) =>
{
  return rsp && rsp.status == 200 && !rsp.data.error;
}
