import React from 'react';

const FormGroup = (props) =>
{
  return (
    <div className="form-group">
      {props.children}
    </div>
  );
}

export default FormGroup;
