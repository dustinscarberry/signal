import React from 'react';

const TextInput = (props) =>
{
  return <input className="form-control" type="text" onChange={props.handleChange} value={props.value} name={props.name}/>
}

TextInput.defaultProps = {
  value: undefined,
  handleChange: undefined,
  name: undefined
};

export default TextInput;
