import React from 'react';

const ContentBlock = (props) =>
{
  return (
    <div className="content-block">
      <div className="content-block-inner">
        {props.children}
      </div>
    </div>
  );
}

export default ContentBlock;
