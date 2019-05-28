export const isValidResponse = (rsp) =>
{
  return rsp && rsp.status == 200 && !rsp.data.error;
}

export const getYouTubeSourceURL = (attrs) =>
{
  let src = 'https://www.youtube.com/embed/';
  src += attrs.sourceID;
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

export const getVimeoSourceURL = (attrs) =>
{
  let src = 'https://player.vimeo.com/video/';
  src += attrs.sourceID;
  src += '?title=0';
  src += '&portrait=0';
  src += '&byline=0';
  src += '&badge=0';
  src += '&autoplay=0';

  return src;
}
