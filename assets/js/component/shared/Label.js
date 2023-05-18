const Label = ({title, hint}) => {
  let hintNode = undefined;
  if (hint)
    hintNode = <span className="label-hint">- {hint}</span>;

  return <label>{title}{hintNode}</label>
}

export default Label;
