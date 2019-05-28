import React from 'react';

const TextInput = (props) =>
{
  return <input className="form-control" type="text" onChange={props.handleChange} value={props.value}/>
}

export default TextInput;
