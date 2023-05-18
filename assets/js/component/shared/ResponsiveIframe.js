import Iframe from './Iframe';

const ResponsiveIframe = ({src}) => {
  return <div className="embed-responsive embed-responsive-16by9">
    <Iframe src={src}/>
  </div>
}

export default ResponsiveIframe;
