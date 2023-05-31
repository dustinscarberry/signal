import axios from 'axios';

export const fetchWidgets = async () => {
  return await axios.get('/api/v1/widgets');
}

export const createWidget = async (widget) => {
  return await axios.post(
    '/api/v1/widgets',
    {
      type: widget.type,
      sortorder: widget.sortorder,
      attributes: widget.attributes
    }
  );
}

export const updateWidget = async (widget) => {
  return await axios.patch(
    '/api/v1/widgets/' + widget.id,
    {
      type: widget.type,
      sortorder: widget.sortorder,
      attributes: widget.attributes
    }
  );
}

export const deleteWidget = async (widgetID) => {
  return await axios.delete(
    '/api/v1/widgets/' + widgetID
  );
}

export const updateWidgetOrder = async (widgetIDs) => {
  return await axios.patch(
    '/api/v1/widgetsorder',
    {widgetIDs: widgetIDs}
  );
}