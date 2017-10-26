<?php

namespace App\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Entity\Category;

class ComponentController extends Controller
{    
    /**
     * @Route("/banner", name="component_banner")
     */
    public function bannerAction()
    {
        $em = $this->getDoctrine()->getManager();
        $banners = $em->getRepository('AppFrontBundle:Banner')->findAll();
        return $this->render('AppWebBundle:Component:banner.html.twig', array('banners' => $banners));
    }
    
    /**
     * @Route("/anon/cart/{id}", name="anon_cart")
     */
    public function anonCartAction(Request $request, $id) 
    {
        if(!$this->getUser()){
            return $this->redirect($this->generateUrl('cart_page'));
        }
        
        $em = $this->getDoctrine()->getManager();
        $anon_cart = $em->getRepository('AppFrontBundle:Cart')->find($id);
        
        $cart = $this->getUser()->getCart();
        $cart->setItems(new \Doctrine\Common\Collections\ArrayCollection());
        $em->persist($cart);
        
        foreach($anon_cart->getItems() as $item){
            $item->setCart($cart);
            $em->persist($item);
        }
        
        $em->flush();
        
        return $this->redirect($this->generateUrl('place_order'));
    }
}
