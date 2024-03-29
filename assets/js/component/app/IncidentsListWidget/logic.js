import axios from 'axios';

export const fetchWidgetData = async (id) => {
  return await axios.get('/api/v1/widgetsdata/' + id);
}

export const getFormattedDateTime = (timestamp) => {
  if (!timestamp)
    return undefined;

  const date = new Date(timestamp * 1000);
  return date.toLocaleString(
    'default',
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

export const getStatusIconClasses = (iconType) => {
  const classes = ['status-icon'];
  classes.push('status-icon-' + iconType);
  return classes;
}

//convert new lines to html breaks
export const nl2br = (text) => {
  return text.split('\n').map((item, key) => {
    return <span key={key}>{item}<br/></span>;
  })
}
