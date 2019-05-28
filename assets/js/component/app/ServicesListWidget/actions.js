export const getStatusBubbleClasses = (statusType) =>
{
  const classes = ['status-bubble'];

  if (statusType == 'ok')
    classes.push('status-bubble-ok');
  else if (statusType == 'issue')
    classes.push('status-bubble-issue');
  else if (statusType == 'error')
    classes.push('status-bubble-error');
  else if (statusType == 'offline')
    classes.push('status-bubble-offline')

  return classes;
}

export const isValidResponse = (rsp) =>
{
  return rsp && rsp.status == 200 && !rsp.data.error;
}
