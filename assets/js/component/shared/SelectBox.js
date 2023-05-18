const SelectBox = ({name, value, options, useBlank, handleChange}) => {
  let newOptions = options;
  if (useBlank)
    newOptions.unshift({key: '', value: ''});

  const optionNodes = newOptions.map(option => {
    return <option key={option.key} value={option.key}>{option.value}</option>
  });

  return <select className="form-control" onChange={handleChange} value={value} name={name}>
    {optionNodes}
  </select>
}

SelectBox.defaultProps = {
  options: [],
  value: undefined,
  handleChange: undefined,
  useBlank: true,
  name: undefined
};

export default SelectBox;
