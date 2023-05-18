import Service from './Service';

const ServicesGroup = (props) => {
  const serviceNodes = props.services.map((service, index) => {
    return <Service service={service} key={index}/>;
  });

  return <div className="service-list-group">
    <h4 className="service-group-name">{props.groupName}</h4>
    {serviceNodes}
  </div>
}

export default ServicesGroup;
