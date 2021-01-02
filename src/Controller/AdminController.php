<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;


/**
* @Route("/admin", name="admin_")
*/
class AdminController extends AbstractController
{
 /**
 * @Route("/utilisateurs", name="utilisateurs")
 */
 public function usersList(UserRepository $user)
  {
    return $this->render("admin/users.html.twig",[
    'users' => $user->findAll()
    ]);
 }

 /**
 * @Route("/utilisateurs/modifier/{id}", name="modifier_utilisateur")
 */
 public function editUser(Request $request, User $user, EntityManagerInterface $em) {

 $form = $this->createForm(EditUserType::class,$user);

 $form->handleRequest($request);
 if($form->isSubmitted() && $form->isValid()) {
 $em->flush();

 return $this->redirectToRoute('admin_utilisateurs');
 }

 return $this->render('admin/editUser.html.twig', ['formUser' => $form->createView()]);
 }

  /**
     * @Route("/utilisateur/status/{id}" , name="status_utilisateur")

     */
    public function activerAction(User $utilisateur)
    {
        $utilisateur->setActive(!$utilisateur->getActive());
        $em = $this->getDoctrine()->getManager();
        $em->persist($utilisateur);
        $em->flush();

        return $this->redirectToRoute('admin_utilisateurs');
    }



}
