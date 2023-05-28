import axios from 'axios';

export const fetchWidgetData = async (id) => {
  return await axios.get('/api/v1/widgetsdata/' + id);
}
