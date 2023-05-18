const Link = ({href, title}) => {
  return <a className="custom-link" href={href}>{title}</a>
}

Link.defaultProps = {
  href: '',
  title: ''
};

export default Link;
