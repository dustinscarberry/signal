import ContentBlock from '../ContentBlock';
import ServicesGroup from './ServicesGroup';
import ServicesList from './ServicesList';

const View = (props) => {
  //calculate nodes
  let serviceWidgetNodes = [];
  let serviceWidgetNodesOne = [];
  let serviceWidgetNodesTwo = [];

  if (props.useGroups)
  {
    const keys = Object.keys(props.services);

    serviceWidgetNodes = keys.map((key, index) => {
      return <ServicesGroup groupName={key} services={props.services[key]} key={index}/>;
    });

    if (props.layout == 'twocolumn')
    {
      if (serviceWidgetNodes.length > 1)
      {
        const halfwayIndex = Math.ceil(serviceWidgetNodes.length / 2);

        serviceWidgetNodesOne = serviceWidgetNodes.slice(0, halfwayIndex);
        serviceWidgetNodesTwo = serviceWidgetNodes.slice(halfwayIndex);
      }
      else
        serviceWidgetNodesOne = serviceWidgetNodes;
    }
  }
  else
  {
    var flattenedServices = [];

    const keys = Object.keys(props.services);
    for (const key of keys)
    {
      for (const service of props.services[key])
        flattenedServices.push(service);
    }

    if (props.layout == 'twocolumn')
    {
      if (flattenedServices.length > 1)
      {
        const halfwayIndex = Math.ceil(flattenedServices.length / 2);

        const flattenedServicesOne = flattenedServices.slice(0, halfwayIndex);
        const flattenedServicesTwo = flattenedServices.slice(halfwayIndex);

        serviceWidgetNodesOne = <ServicesList services={flattenedServicesOne}/>;
        serviceWidgetNodesTwo = <ServicesList services={flattenedServicesTwo}/>;
      }
      else
        serviceWidgetNodesOne = <ServicesList services={flattenedServices}/>;
    }
    else if (props.layout == 'onecolumn')
    {
      serviceWidgetNodes = <ServicesList services={flattenedServices}/>;
    }
  }

  //return nodes
  if (props.layout == 'onecolumn')
  {
    return (
      <ContentBlock>
        <div className="container-fluid">
          <h2 className="widget-header">Services</h2>
          <div className="row">
            <div className="col-lg-12">
              {serviceWidgetNodes}
            </div>
          </div>
        </div>
      </ContentBlock>
    );
  }
  else if (props.layout == 'twocolumn')
  {
    return <ContentBlock>
      <div className="container-fluid">
        <h2 className="widget-header">Services</h2>
        <div className="row">
          <div className="col-lg-6">
            {serviceWidgetNodesOne}
          </div>
          <div className="col-lg-6">
            {serviceWidgetNodesTwo}
          </div>
        </div>
      </div>
    </ContentBlock>
  }
}

View.defaultProps = {
  layout: 'fullwidth',
  useGroups: false
};

export default View;
