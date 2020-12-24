<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends AbstractController
{
/**
* @Route("/connexion", name="security_login")
*/
public function login(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig',[
            'lastUsername'=>$lastUsername,'error' => $error]);
    }

    /**
 * @Route("/inscription", name="security_registration")
 */
 public function registration(Request $request, EntityManagerInterface $em , UserPasswordEncoderInterface $encoder )
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user,$user->getPassword());
            $hash2 = $encoder->encodePassword($user,$user->getConfirmPassword());
            $user->setPassword($hash);
            $user->setConfirmPassword($hash2);
        //l'objet $em sera affecté automatiquement grâce à l'injection des dépendances de symfony 4

        $em->persist($user);
        $em->flush();
        
        }
        return $this->render('security/registration.html.twig',[
            'form' =>$form->createView()]);
    }

      
/**
* @Route("/deconnexion", name="security_logout")
*/
    public function logout(){}
     


}
