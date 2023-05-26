import Service from './Service';

const ServicesGroup = ({services, groupName}) => {
  return <div className="service-list-group">
    <h4 className="service-group-name">{groupName}</h4>
    {services.map((service, i) => {
      return <Service key={i} service={service}/>
    })}
  </div>
}

export default ServicesGroup;
