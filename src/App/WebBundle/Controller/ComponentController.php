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
     * @Route("/offer", name="component_offer")
     */
    public function offerAction()
    {
        $now = new \DateTime('now');
        $now->setTime(0, 0, 0);
        $offers = $this->getDoctrine()->getManager()->getRepository("AppFrontBundle:Offer")->createQueryBuilder('o')
            ->where('o.status = :s and o.expiry >= :now')
            ->setParameter('s', true)
            ->setParameter('now', $now)
            ->setFirstResult(0)
            ->setMaxResults(2)
            ->getQuery()
            ->getResult();
        return $this->render('AppWebBundle:Component:offer.html.twig', array('offers' => $offers));
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
        foreach($cart->getItems() as $item){
            $em->remove($item);
        }
        $em->flush();
        
        foreach($anon_cart->getItems() as $item){
            $item->setCart($cart);
            $em->persist($item);
        }
        
        $em->flush();
        
        return $this->redirect($this->generateUrl('place_order'));
    }
}
