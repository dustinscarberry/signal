import axios from 'axios';

export const fetchWidgetData = async (id) => {
  return await axios.get('/api/v1/widgetsdata/' + id);
}

export const getMessageType = (serviceStatuses) => {
  const typeRanking = [
    'ok',
    'offline',
    'issue',
    'error'
  ];

  var typeRank = 0;
  for (status of serviceStatuses) {
    let newRank = typeRanking.indexOf(status);
    if (newRank > typeRank)
      typeRank = newRank;

    if (typeRank == serviceStatuses.length - 1)
      break;
  }

  return typeRanking[typeRank];
}

export const getMessageText = (type) => {
  if (type == 'ok')
    return 'All Services Operational';
  else if (type == 'issue')
    return 'Some Services Experiencing Issues';
  else if (type == 'error')
    return 'Some Services Experiencing Major Outages';
  else if (type == 'offline')
    return 'Some Services Are Offline';
}

export const getStatusClasses = (type) => {
  if (type == 'ok')
    return ['status-overview-ok'];
  else if (type == 'issue')
    return ['status-overview-issue'];
  else if (type == 'error')
    return ['status-overview-error'];
  else if (type == 'offline')
    return ['status-overview-offline'];
}