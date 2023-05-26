import { useState, useEffect } from 'react';
import { fetchWidgetData, getSourceURL } from './logic';
import { isOk } from '../../../logic/utils';
import Loader from '../../shared/Loader';
import View from './View';

const VideoEmbedWidget = ({id}) => {
  const [source, setSource] = useState();
  const [sourceId, setSourceId] = useState();

  useEffect(() => {
    loadVideo();
  }, []);

  const loadVideo = async () => {
    const rsp = await fetchWidgetData(id);

    if (isOk(rsp)) {
      const attributes = rsp.data.data.options.attributes;

      setSource(attributes.source);
      setSourceId(attributes.sourceID);
    }
  }

  if (!source || !sourceId)
    return <Loader/>

  return <View sourceURL={getSourceURL(source, sourceId)}/>
}

VideoEmbedWidget.defaultProps = {
  id: undefined
};

export default VideoEmbedWidget;