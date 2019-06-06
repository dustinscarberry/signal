<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\ServiceCategory;
use App\Form\ServiceCategoryType;

class ServiceCategoryApiController extends ApiController
{
  /**
  * @Route("/api/v1/servicecategories", name="getServiceCategories", methods={"GET"})
  * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function getServiceCategories()
  {
    $serviceCategories = $this->getDoctrine()
      ->getRepository(ServiceCategory::class)
      ->findAllNotDeleted();

    return $this->respond($serviceCategories);
  }

  /**
  * @Route("/api/v1/servicecategories/{guid}", name="getServiceCategory", methods={"GET"})
  * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function getServiceCategory($guid)
  {
    //get service category
    $serviceCategory = $this->getDoctrine()
      ->getRepository(ServiceCategory::class)
      ->findByGuid($guid);

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
  public function createServiceCategory(Request $req)
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
        $serviceCategory = $form->getData();

        $em = $this->getDoctrine()->getManager();
        $em->persist($serviceCategory);
        $em->flush();

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
   * @Route("/api/v1/servicecategories/{guid}", name="updateServiceCategory", methods={"PATCH"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function updateServiceCategory(
    $guid,
    Request $request
  )
  {
    try
    {
      //get service from database
      $serviceCategory = $this->getDoctrine()
        ->getRepository(ServiceCategory::class)
        ->findByGuid($guid);

      if (!$serviceCategory)
        throw new \Exception('No service category found');

      //create form object for service
      $form = $this->createForm(ServiceCategoryType::class, $serviceCategory, ['csrf_protection' => false]);

      //submit form
      $data = json_decode($request->getContent(), true);
      $form->submit($data, false);

      //save form data to database if posted and validated
      if ($form->isSubmitted() && $form->isValid())
      {
        $serviceCategory = $form->getData();

        $this->getDoctrine()->getManager()->flush();

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
  * @Route("/api/v1/servicecategories/{guid}", name="deleteServiceCategory", methods={"DELETE"})
  * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function deleteServiceCategory($guid)
  {
    try
    {
      //get service category
      $serviceCategory = $this->getDoctrine()
        ->getRepository(ServiceCategory::class)
        ->findByGuid($guid);

      //check for valid service category
      if (!$serviceCategory)
        return $this->respondWithErrors(['Invalid service category']);

      //delete service category
      $serviceCategory->setDeletedOn(time());
      $serviceCategory->setDeletedBy($this->getUser());
      $this->getDoctrine()->getManager()->flush();

      //respond with object
      return $this->respond($serviceCategory);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }
}
