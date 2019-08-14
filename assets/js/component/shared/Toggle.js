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

Toggle.defaultProps = {
  value: false,
  handleChange: undefined,
  name: undefined
};

export default Toggle;
