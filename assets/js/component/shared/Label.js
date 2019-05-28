import React from 'react';

const Label = (props) =>
{
  let hintNode = undefined;
  if (props.hint)
    hintNode = <span className="label-hint">- {props.hint}</span>;

  return <label>{props.title}{hintNode}</label>;
}

export default Label;
