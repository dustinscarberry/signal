const ContentBlock = ({children}) => {
  return <div className="content-block">
    <div className="content-block-inner">
      {children}
    </div>
  </div>
}

export default ContentBlock;