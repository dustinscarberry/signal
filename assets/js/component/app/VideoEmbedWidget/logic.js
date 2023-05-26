import axios from 'axios';

export const fetchWidgetData = async (id) => {
  return await axios.get('/api/v1/widgetsdata/' + id);
}

export const getSourceURL = (source, sourceId) => {
  if (source == 'youtube')
    return getYouTubeSourceURL(sourceId);
  else if (source == 'vimeo')
    return getVimeoSourceURL(sourceId);
  else
    return '';
}

const getYouTubeSourceURL = (sourceId) => {
  let src = 'https://www.youtube.com/embed/';
  src += sourceId;
  src += '?modestbranding=1';
  src += '&rel=0';
  src += '&showinfo=0';
  src += '&autohide=0';
  src += '&iv_load_policy=3';
  src += '&color=white';
  src += '&theme=dark';
  src += '&autoplay=0';

  return src;
}

const getVimeoSourceURL = (sourceId) => {
  let src = 'https://player.vimeo.com/video/';
  src += sourceId;
  src += '?title=0';
  src += '&portrait=0';
  src += '&byline=0';
  src += '&badge=0';
  src += '&autoplay=0';

  return src;
}