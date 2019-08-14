import React from 'react';

const NumberInput = (props) =>
{
  return <input className="form-control" type="number" onChange={props.handleChange} value={props.value} name={props.name}/>
}

NumberInput.defaultProps = {
  value: undefined,
  handleChange: undefined,
  name: undefined
};

export default NumberInput;
