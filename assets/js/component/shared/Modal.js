import { Component } from 'react';

class Modal extends Component
{
  constructor(props) {
    super(props);

    this.close = this.close.bind(this);
  }

  close()
  {
    const target = event.target.className;
    if (target == 'modal-close' || target == 'modal-inner')
      this.props.handleModalToggle();
  }

  render() {
    const backdropClasses = ['modal-backdrop'];
    const modalClasses = ['modal'];

    if (this.props.isOpen)
    {
      backdropClasses.push('show');
      modalClasses.push('show');
    }

    if (this.props.title.trim() != '')
      modalClasses.push('show-title');

    return <div>
      <div className={modalClasses.join(' ')} onClick={this.close}>
        <div className="modal-inner">
          <div className="modal-content">
            <div className="modal-header">
              <span className="modal-title">{this.props.title}</span>
              <button type="button" className="modal-close" aria-hidden="true" onClick={this.close}></button>
            </div>
            <div className="modal-body">
              {this.props.children}
            </div>
          </div>
        </div>
      </div>
      <div className={backdropClasses.join(' ')}></div>
    </div>
  }
}

Modal.defaultProps = {
  title: ''
};

export default Modal;
