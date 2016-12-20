<?php

namespace App\WebBundle\Service;

use Symfony\Component\DependencyInjection\Container;
use App\FrontBundle\Entity\Cart;
use App\FrontBundle\Entity\WishList;
use App\FrontBundle\Entity\User as UserEntity;
use App\FrontBundle\Entity\StockEntry;

/**
 * Description of User
 *
 * @author bebeto
 */
class User {
    
    /*
     * Container
     */
    private $container;
    
    /*
     * UserEntity
     */
    private $user = null;
    
    /*
     * Constructor
     */
    public function __construct(Container $container) {
        $this->container = $container;
        if($token = $this->container->get('security.token_storage')->getToken()){
            $this->user = $this->container->get('security.token_storage')->getToken()->getUser();
        }
    }
    
    /*
     * Get count of cart items
     */
    public function getCart(){
        $em = $this->container->get('doctrine')->getManager();
        if($this->user instanceof UserEntity){
            $cart = $this->user->getCart();
        } else {
            $cart = $em->getRepository('AppFrontBundle:Cart')->findOneBySessionId(session_id());
        }
        
        if(!$cart instanceof Cart){
            $cart = new Cart();
        }
        
        return $cart;
    }
    
    /*
     * Get count of cart items
     */
    public function getWishlist() {
        $em = $this->container->get('doctrine')->getManager();
        if($this->user instanceof UserEntity){
            $wishlist = $this->user->getWishlist();
        } else {
            $wishlist = $em->getRepository('AppFrontBundle:WishList')->findOneBySessionId(session_id());
        }
        
        if(!$wishlist instanceof WishList){
            $wishlist = new WishList();
        }
        
        return $wishlist;
    }
    
    public function inCart(StockEntry $entry) {
        $cart = $this->getCart();
        foreach($cart->getItems() as $item){
            if($item->getEntry()->getId() == $entry->getId()){
                return true;
            }
        }
        
        return false;
    }
    
    public function inWishList(StockEntry $entry) {
        $wishlist = $this->getWishlist();
        foreach($wishlist->getItems() as $item){
            if($item->getEntry()->getId() == $entry->getId()){
                return true;
            }
        }
        
        return false;
    }
    
}
