import { useState, useEffect } from 'react';
import { isValidResponse, fetchWidgetData, getSourceURL } from './logic';
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

    if (isValidResponse(rsp)) {
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