import React from 'react';
import View from './View';
import { Provider } from 'react-redux';
import store from './redux/store';

const AppWidgetCustomizer = (props) =>
{
  return (
    <Provider store={store}>
      <View/>
    </Provider>
  );
}

export default AppWidgetCustomizer;
