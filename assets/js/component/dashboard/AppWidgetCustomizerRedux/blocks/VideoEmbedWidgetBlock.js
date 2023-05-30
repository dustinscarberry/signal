import ResponsiveIframe from '../../../shared/ResponsiveIframe';
import FormGroup from '../../../shared/FormGroup';
import Label from '../../../shared/Label';
import TextInput from '../../../shared/TextInput';

const VideoEmbedWidgetBlock = ({widget, handleUpdateAttributes, toggleIsSaved}) => {
  const changeURL = (e) => {
    let url = e.target.value.trim();

    handleUpdateAttributes({
      source: undefined,
      url: url,
      sourceID: undefined
    });

    toggleIsSaved(false);

    if (url) {
      const compareURL = url.toLowerCase();

      if (
        compareURL.indexOf('youtube') !== -1
        || compareURL.indexOf('youtu.be') !== -1
      ) {
        let matches = url.match(/(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\?v=|\/)([^\s&]+)/);

        if (matches)
          handleUpdateAttributes({
            source: 'youtube',
            sourceID: matches[1]
          });
      } else if (compareURL.indexOf('vimeo') !== -1) {
        let matches = url.match(/https?:\/\/(www\.)?vimeo.com\/(\d+)($|\/)/);

        if (matches)
          handleUpdateAttributes({
            source: 'vimeo',
            sourceID: matches[2]
          });
      }
    }
  }

  const getVideoPreview = () => {
    const { source, sourceID } = widget.attributes;

    let src = '';
    if (source == 'youtube') {
      src = 'https://www.youtube.com/embed/';
      src += sourceID;
      src += '?modestbranding=1';
      src += '&rel=0';
      src += '&showinfo=0';
      src += '&autohide=0';
      src += '&iv_load_policy=3';
      src += '&color=white';
      src += '&theme=dark';
      src += '&autoplay=0';
    } else if (source == 'vimeo') {
      src = 'https://player.vimeo.com/video/';
      src += sourceID;
      src += '?title=0';
      src += '&portrait=0';
      src += '&byline=0';
      src += '&badge=0';
      src += '&autoplay=0';
    }

    return src;
  }

  return <div>
    <FormGroup>
      <Label title="Video URL" hint="Embed address"/>
      <TextInput
        handleChange={changeURL}
        value={widget.attributes.url}
      />
    </FormGroup>
    <div className="video-embed-widget-preview-wrapper">
      <ResponsiveIframe src={getVideoPreview()}/>
    </div>
  </div>
}

VideoEmbedWidgetBlock.defaultProps = {
  url: undefined,
  source: undefined,
  sourceID: undefined
}

export default VideoEmbedWidgetBlock;