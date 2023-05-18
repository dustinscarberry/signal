const NumberInput = ({name, value, handleChange}) => {
  return <input className="form-control" type="number" onChange={handleChange} value={value} name={name}/>
}

NumberInput.defaultProps = {
  name: undefined,
  value: undefined,
  handleChange: undefined
};

export default NumberInput;
