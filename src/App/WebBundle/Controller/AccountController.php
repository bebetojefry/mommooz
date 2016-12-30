<?php

namespace App\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Entity\CartItem;
use App\FrontBundle\Entity\WishListItem;
use App\FrontBundle\Entity\Consumer;
use App\FrontBundle\Form\RegisterType;
use App\FrontBundle\Entity\Address;

class AccountController extends Controller
{
    /**
     * @Route("/register", name="register_page")
     */
    public function registerAction(Request $request){
        if($this->getUser()){            
            return $this->redirect($this->generateUrl('home'));
        }
        $em = $this->getDoctrine()->getManager();
        $consumer = $this->get('app.front.entity.consumer');
        $consumer->regenerateSalt();
        $consumer->addAddress(new Address());
        $form = $this->createForm(new RegisterType(), $consumer);
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $consumer = $form->getData();
                $consumer->setUsername($consumer->getEmail());
                $consumer->setLocale('en');
                $consumer->setCreatedOn(new \DateTime('now'));
                $consumer->setStatus(true);
                $em->persist($consumer);
                $em->flush();
                return $this->redirect($this->generateUrl('register_thank_you'));
            }
        }
        
        return $this->render('AppWebBundle:Account:register.html.twig', array('form' => $form->createView()));
    }
    
    /**
     * @Route("/register/thankyou", name="register_thank_you")
     */
    public function thankyouAction()
    {
        if($this->getUser()){            
            return $this->redirect($this->generateUrl('home'));
        }
        return $this->render('AppWebBundle:Account:thankyou.html.twig');
    }
    
    /**
     * @Route("/login", name="login_page")
     */
    public function loginAction(){
        if($this->getUser()){            
            return $this->redirect($this->generateUrl('home'));
        }
        
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('AppWebBundle:Account:login.html.twig', array(
            // last username entered by the user
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }
    
    /**
     * @Route("/", name="account_home")
     */
    public function accountAction(Request $request)
    {
        $consumer = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $obj = new \stdClass();
        $obj->password = '';
        $form = $this->createFormBuilder($obj)
            ->add('password', 'repeated', array(
                'type' => 'password',
                'required' => true,
                'invalid_message' => 'The password fields must match.',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
            ->getForm();
        
        $message = '';
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $obj = $form->getData();
                $encoder = $this->get('security.encoder_factory')->getEncoder($consumer);
                $password = $encoder->encodePassword($obj->password, $consumer->getSalt());
                $consumer->setPassword($password);
                $em->persist($consumer);
                $em->flush();
                $message = 'Password successfully changed.';
            } else {
                $message = 'Password doesn\'t match.';
            }
        }
        
        return $this->render('AppWebBundle:Account:index.html.twig',
            array('form' => $form->createView(), 'message' => $message)
        );
    }
    
    /**
     * @Route("/cart", name="cart_page")
     */
    public function cartAction()
    {
        return $this->render('AppWebBundle:Account:cart.html.twig');
    }
    
    /**
     * @Route("/cart/{id}/delete", name="cart_delete", options={"expose"=true})
     */
    public function cartdeleteAction(CartItem $item)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($item);
        $em->flush();
        
        return $this->redirect($this->generateUrl('cart_page'));
    }
    
    /**
     * @Route("/wishlist", name="wishlist_page")
     */
    public function wishlistAction()
    {
        return $this->render('AppWebBundle:Account:wishlist.html.twig');
    }
    
    /**
     * @Route("/wishlist/{id}/delete", name="wishlist_delete", options={"expose"=true})
     */
    public function wishlistdeleteAction(WishListItem $item)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($item);
        $em->flush();
        
        return $this->redirect($this->generateUrl('wishlist_page'));
    }
}
