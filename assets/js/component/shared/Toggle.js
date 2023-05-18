const Toggle = ({name, value, handleChange}) => {
  return <div className="ui toggle checkbox">
    <input type="checkbox" name={name} checked={value} onChange={handleChange}/>
    <label></label>
  </div>
}

Toggle.defaultProps = {
  name: undefined,
  value: false,
  handleChange: undefined
};

export default Toggle;
