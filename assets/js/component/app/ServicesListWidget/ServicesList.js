import Service from './Service';

const ServicesList = ({services}) => {
  return <div className="service-list">
    {services.map((service, i) => {
      return <Service key={i} service={service}/>
    })}
  </div>
}

export default ServicesList;
