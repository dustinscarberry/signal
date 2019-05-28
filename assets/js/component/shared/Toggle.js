import React from 'react';

const Toggle = (props) =>
{
  return (
    <div className="ui toggle checkbox">
      <input type="checkbox" name={props.name} checked={props.value} onChange={props.handleChange}/>
      <label></label>
    </div>
  );
}

export default Toggle;
