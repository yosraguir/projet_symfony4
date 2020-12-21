<?php

namespace App\Controller;
use App\Entity\Category;
use App\Entity\Article;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    /**
     * @Route("/category/newCat", name="new_category")
     * Method({"GET", "POST"})
     */
 public function newCategory(Request $request) {
    $category = new Category();
    $form = $this->createForm(CategoryType::class,$category);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
    $article = $form->getData();
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($category);
    $entityManager->flush();
    return $this->redirectToRoute('article_list');
    }
   return $this->render('category/newCategory.html.twig',['form'=>
   $form->createView()]);
    }
   
}
