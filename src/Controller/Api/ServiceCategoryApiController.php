<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\ExpressionLanguage\Expression;
use App\Entity\ServiceCategory;
use App\Form\ServiceCategoryType;
use App\Service\Factory\ServiceCategoryFactory;

class ServiceCategoryApiController extends ApiController
{
  #[Route('/api/v1/servicecategories', name: 'getServiceCategories', methods: ['GET'])]
  #[IsGranted(new Expression("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')"))]
  public function getServiceCategories(ServiceCategoryFactory $serviceCategoryFactory)
  {
    $serviceCategories = $serviceCategoryFactory->getServiceCategories();
    return $this->respond($serviceCategories);
  }

  #[Route('/api/v1/servicecategories/{hashId}', name: 'getServiceCategory', methods: ['GET'])]
  #[IsGranted(new Expression("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')"))]
  public function getServiceCategory($hashId, ServiceCategoryFactory $serviceCategoryFactory)
  {
    //get service category
    $serviceCategory = $serviceCategoryFactory->getServiceCategory($hashId);

    //check for valid service category
    if (!$serviceCategory)
      return $this->respondWithErrors(['Invalid data']);

    //respond with object
    return $this->respond($serviceCategory);
  }

  #[Route('/api/v1/servicecategories', name: 'createServiceCategory', methods: ['POST'])]
  #[IsGranted(new Expression("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')"))]
  public function createServiceCategory(Request $req, ServiceCategoryFactory $serviceCategoryFactory)
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
        $serviceCategoryFactory->createServiceCategory($serviceCategory);

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

  #[Route('/api/v1/servicecategories/{hashId}', name: 'updateServiceCategory', methods: ['PATCH'])]
  #[IsGranted(new Expression("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')"))]
  public function updateServiceCategory($hashId, Request $req, ServiceCategoryFactory $serviceCategoryFactory)
  {
    try
    {
      //get service from database
      $serviceCategory = $serviceCategoryFactory->getServiceCategory($hashId);

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
        $serviceCategoryFactory->updateServiceCategory();

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

  #[Route('/api/v1/servicecategories/{hashId}', name: 'deleteServiceCategory', methods: ['DELETE'])]
  #[IsGranted(new Expression("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')"))]
  public function deleteServiceCategory($hashId, ServiceCategoryFactory $serviceCategoryFactory)
  {
    try
    {
      //get service category
      $serviceCategory = $serviceCategoryFactory->getServiceCategory($hashId);

      //check for valid service category
      if (!$serviceCategory)
        return $this->respondWithErrors(['Invalid service category']);

      $serviceCategoryFactory->deleteServiceCategory($serviceCategory);

      //respond with object
      return $this->respond($serviceCategory);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }
}
