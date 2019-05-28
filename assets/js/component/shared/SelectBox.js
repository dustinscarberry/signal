import React from 'react';

const SelectBox = (props) =>
{
  let options = props.options;
  if (props.useBlank)
    options.unshift({key: '', value: ''});

  const optionNodes = options.map(option => {
    return <option key={option.key} value={option.key}>{option.value}</option>
  });

  return (
    <select className="form-control" onChange={props.handleChange} value={props.value}>
      {optionNodes}
    </select>
  );
}

SelectBox.defaultProps = {
  options: [],
  value: undefined,
  handleChange: undefined,
  useBlank: true
};

export default SelectBox;
