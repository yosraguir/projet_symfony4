<?php
namespace App\Controller;
use App\Entity\Article;
use App\Entity\Category;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
Use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class IndexController extends AbstractController
{
 /**
 *@Route("/article",name="article_list")
 */
 public function home()
 {
  $articles= $this->getDoctrine()->getRepository(Article::class)->findAll();
  return $this->render('articles/index.html.twig',['articles'=> $articles]);
    }
   

 /**
  * @Route("/")
  */
public function index(){
    return $this->render('baseDashbord.html.twig');
}

/**
 * @Route("/article/save")
 */
public function save() {
  $entityManager = $this->getDoctrine()->getManager();
  $article = new Article();
  $article->setNom('Article 3');
  $article->setPrix(3000);
 
  $entityManager->persist($article);
  $entityManager->flush();
  return new Response('Article enregisté avec id '.$article->getId());
  }

  /**
 * @Route("/article/new", name="new_article")
 * Method({"GET", "POST"})
 */
 public function new(Request $request) {
  $article = new Article();
  $form = $this->createForm(ArticleType::class, $article);
  $form->handleRequest($request);
  if($form->isSubmitted() && $form->isValid()) {
    $article = $form->getData();
   
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($article);
    $entityManager->flush();
   
    return $this->redirectToRoute('article_list');
    }
    return $this->render('articles/new.html.twig',['form' => $form->createView()]);

 }
 /**
 * @Route("/article/{id}", name="article_show")
 */
public function show($id) {
  $article = $this->getDoctrine()->getRepository(Article::class)
  ->find($id);
  return $this->render('articles/show.html.twig',
  array('article' => $article));
   }

   //

   /**
 * @Route("/article/edit/{id}", name="edit_article")
 * Method({"GET", "POST"})
 */
 public function edit(Request $request, $id) {
  $article = new Article();
  $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
 
  $form = $this->createForm(ArticleType::class, $article);
  $form->handleRequest($request);
  if($form->isSubmitted() && $form->isValid()) {
 
  $entityManager = $this->getDoctrine()->getManager();
  $entityManager->flush();
 
  return $this->redirectToRoute('article_list');
  }
 
  return $this->render('articles/edit.html.twig', ['form' => $form->createView()]);
  }
 
  /**
 * @Route("/article/delete/{id}",name="delete_article")
 * @Method({"DELETE"})
 */
 public function delete(Request $request, $id) {
  $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
 
  $entityManager = $this->getDoctrine()->getManager();
  $entityManager->remove($article);
  $entityManager->flush();
 
  $response = new Response();
  $response->send();
  return $this->redirectToRoute('article_list');
  }
 
  

}