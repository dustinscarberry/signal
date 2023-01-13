<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\ServiceCategory;
use App\Form\ServiceCategoryType;
use App\Service\Factory\ServiceCategoryFactory;

class ServiceCategoryController extends AbstractController
{
  #[Route('/dashboard/servicecategories', name: 'viewServiceCategories')]
  public function viewall(ServiceCategoryFactory $serviceCategoryFactory)
  {
    $serviceCategories = $serviceCategoryFactory->getServiceCategories();

    return $this->render('dashboard/servicecategory/viewall.html.twig', [
      'serviceCategories' => $serviceCategories
    ]);
  }

  #[Route('/dashboard/servicecategories/add', name: 'addServiceCategory')]
  public function add(Request $req, ServiceCategoryFactory $serviceCategoryFactory)
  {
    //create service category object
    $serviceCategory = new ServiceCategory();

    //create form object for service category
    $form = $this->createForm(ServiceCategoryType::class, $serviceCategory);

    //handle form request if posted
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $serviceCategoryFactory->createServiceCategory($serviceCategory);

      $this->addFlash('success', 'Service Category created');
      return $this->redirectToRoute('viewServiceCategories');
    }

    //render service categories add page
    return $this->render('dashboard/servicecategory/add.html.twig', [
      'form' => $form->createView()
    ]);
  }

  #[Route('/dashboard/servicecategories/{hashId}', name: 'editServiceCategory')]
  public function edit($hashId, Request $req, ServiceCategoryFactory $serviceCategoryFactory)
  {
    //get service from database
    $serviceCategory = $serviceCategoryFactory->getServiceCategory($hashId);

    //create form object for service
    $form = $this->createForm(ServiceCategoryType::class, $serviceCategory);

    //handle form request if posted
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $serviceCategoryFactory->updateServiceCategory();

      $this->addFlash('success', 'Service Category updated');
      return $this->redirectToRoute('viewServiceCategories');
    }

    //render service add page
    return $this->render('dashboard/servicecategory/edit.html.twig', [
      'form' => $form->createView()
    ]);
  }
}
