<?php

namespace App\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Entity\CartItem;
use App\FrontBundle\Entity\WishListItem;

class AccountController extends Controller
{
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
