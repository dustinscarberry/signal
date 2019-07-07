<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\ServiceCategory;
use App\Form\ServiceCategoryType;
use App\Service\Manager\ServiceCategoryManager;

class ServiceCategoryApiController extends ApiController
{
  /**
  * @Route("/api/v1/servicecategories", name="getServiceCategories", methods={"GET"})
  * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function getServiceCategories(ServiceCategoryManager $serviceCategoryManager)
  {
    $serviceCategories = $serviceCategoryManager->getServiceCategories();
    return $this->respond($serviceCategories);
  }

  /**
  * @Route("/api/v1/servicecategories/{hashId}", name="getServiceCategory", methods={"GET"})
  * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function getServiceCategory($hashId, ServiceCategoryManager $serviceCategoryManager)
  {
    //get service category
    $serviceCategory = $serviceCategoryManager->getServiceCategory($hashId);

    //check for valid service category
    if (!$serviceCategory)
      return $this->respondWithErrors(['Invalid data']);

    //respond with object
    return $this->respond($serviceCategory);
  }

  /**
   * @Route("/api/v1/servicecategories", name="createServiceCategory", methods={"POST"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function createServiceCategory(Request $req, ServiceCategoryManager $serviceCategoryManager)
  {
    try
    {
      //create service category object
      $serviceCategory = new ServiceCategory();

      //create form object for service category
      $form = $this->createForm(
        ServiceCategoryType::class,
        $serviceCategory,
        ['csrf_protection' => false]
      );

      //submit form
      $data = json_decode($req->getContent(), true);
      $form->submit($data);

      //save form data to database if posted and validated
      if ($form->isSubmitted() && $form->isValid())
      {
        $serviceCategoryManager->createServiceCategory($serviceCategory);

        //respond with object
        return $this->respond($serviceCategory);
      }

      return $this->respondWithErrors(['Invalid data']);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/servicecategories/{hashId}", name="updateServiceCategory", methods={"PATCH"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function updateServiceCategory($hashId, Request $req, ServiceCategoryManager $serviceCategoryManager)
  {
    try
    {
      //get service from database
      $serviceCategory = $serviceCategoryManager->getServiceCategory($hashId);

      if (!$serviceCategory)
        throw new \Exception('No service category found');

      //create form object for service
      $form = $this->createForm(ServiceCategoryType::class, $serviceCategory, ['csrf_protection' => false]);

      //submit form
      $data = json_decode($req->getContent(), true);
      $form->submit($data, false);

      //save form data to database if posted and validated
      if ($form->isSubmitted() && $form->isValid())
      {
        $serviceCategoryManager->updateServiceCategory();

        //respond with object
        return $this->respond($serviceCategory);
      }

      return $this->respondWithErrors(['Invalid data']);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
  * @Route("/api/v1/servicecategories/{hashId}", name="deleteServiceCategory", methods={"DELETE"})
  * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function deleteServiceCategory($hashId, ServiceCategoryManager $serviceCategoryManager)
  {
    try
    {
      //get service category
      $serviceCategory = $serviceCategoryManager->getServiceCategory($hashId);

      //check for valid service category
      if (!$serviceCategory)
        return $this->respondWithErrors(['Invalid service category']);

      $serviceCategoryManager->deleteServiceCategory($serviceCategory);

      //respond with object
      return $this->respond($serviceCategory);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }
}
