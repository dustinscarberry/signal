import Service from './Service';

const ServicesList = (props) => {
  const serviceNodes = props.services.map((service, index) => {
    return <Service service={service} key={index}/>;
  });

  return <div className="service-list">
    {serviceNodes}
  </div>
}

export default ServicesList;
