import React from 'react';

const NumberInput = (props) =>
{
  return <input className="form-control" type="number" onChange={props.handleChange} value={props.value}/>
}

export default NumberInput;
