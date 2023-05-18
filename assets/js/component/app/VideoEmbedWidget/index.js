import { Component } from 'react';
import axios from 'axios';
import { isValidResponse, getYouTubeSourceURL, getVimeoSourceURL } from './actions';
import Loader from '../../shared/Loader';
import View from './View';

class VideoEmbedWidget extends Component
{
  constructor(props)
  {
    super(props);

    this.state = {
      source: undefined,
      sourceID: undefined
    };
  }

  componentDidMount()
  {
    this.load();
  }

  async load()
  {
    const rsp = await axios.get(
      '/api/v1/widgetsdata/' + this.props.id
    );

    if (isValidResponse(rsp))
    {
      const attributes = rsp.data.data.options.attributes;

      this.setState({
        source: attributes.source,
        sourceID: attributes.sourceID
      });
    }
  }

  getSourceURL()
  {
    if (this.state.source == 'youtube')
      return getYouTubeSourceURL(this.state);
    else if (this.state.source == 'vimeo')
      return getVimeoSourceURL(this.state);
    else
      return '';
  }

  render() {
    if (!this.state.source || !this.state.sourceID)
      return <Loader/>

    return <View sourceURL={this.getSourceURL()}/>
  }
}

VideoEmbedWidget.defaultProps = {
  id: undefined
};

export default VideoEmbedWidget;
