<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\ServiceCategory;
use App\Form\ServiceCategoryType;

class ServiceCategoryController extends AbstractController
{
  /**
   * @Route("/dashboard/servicecategories", name="viewServiceCategories")
   */
  public function viewall()
  {
    $serviceCategories = $this->getDoctrine()
      ->getRepository(ServiceCategory::class)
      ->findAllNotDeleted();

    return $this->render('dashboard/servicecategory/viewall.html.twig', [
      'serviceCategories' => $serviceCategories
    ]);
  }

  /**
   * @Route("/dashboard/servicecategories/add")
   */
  public function add(Request $request)
  {
    //create service category object
    $serviceCategory = new ServiceCategory();

    //create form object for service category
    $form = $this->createForm(ServiceCategoryType::class, $serviceCategory);

    //handle form request if posted
    $form->handleRequest($request);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $serviceCategory = $form->getData();

      $em = $this->getDoctrine()->getManager();
      $em->persist($serviceCategory);
      $em->flush();

      $this->addFlash('success', 'Service Category created');
      return $this->redirectToRoute('viewServiceCategories');
    }

    //render service categories add page
    return $this->render('dashboard/servicecategory/add.html.twig', [
      'form' => $form->createView()
    ]);
  }

  /**
   * @Route("/dashboard/servicecategories/{serviceCategoryGuid}", name="editServiceCategory")
   */
  public function edit($serviceCategoryGuid, Request $request)
  {
    //get service from database
    $serviceCategory = $this->getDoctrine()
      ->getRepository(ServiceCategory::class)
      ->findByGuid($serviceCategoryGuid);

    //create form object for service
    $form = $this->createForm(ServiceCategoryType::class, $serviceCategory);

    //handle form request if posted
    $form->handleRequest($request);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $service = $form->getData();

      $this->getDoctrine()->getManager()->flush();

      $this->addFlash('success', 'Service Category updated');
      return $this->redirectToRoute('viewServiceCategories');
    }

    //render service add page
    return $this->render('dashboard/servicecategory/edit.html.twig', [
      'form' => $form->createView()
    ]);
  }
}
