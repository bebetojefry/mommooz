<?php

namespace App\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function loginAction(Request $request)
    {
        if($this->getUser()){            
            return $this->redirect($this->generateUrl('app_front_admin_home'));
        }
        
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
		
        return $this->render('AppFrontBundle:User:login.html.twig', array(
            // last username entered by the user
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    public function logincheckAction(){

    }

    public function registerAction(){
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->get('app.front.entity.admin');
        $user->regenerateSalt();
        $user->setUsername('admin');
        $user->setPassword('admin123');
        $user->setFirstname('Mamoos');
        $user->setLastname('');
        $user->setGender(1);
        $user->setLocale('en');
        $user->setPhone('111111');
        $user->setStatus(1);
        $em->persist($user);
        $em->flush();
        
        echo $user->getSalt().'<br/>';
        echo $user->getPassword();
        exit;
    }
}
