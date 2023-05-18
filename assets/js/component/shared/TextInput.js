const TextInput = ({name, value, handleChange}) => {
  return <input className="form-control" type="text" onChange={handleChange} value={value} name={name}/>
}

TextInput.defaultProps = {
  name: undefined,
  value: undefined,
  handleChange: undefined
};

export default TextInput;
