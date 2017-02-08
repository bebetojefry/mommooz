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
    
    public function getAllPopularcategories(){
        $em = $this->container->get('doctrine')->getManager();
        return $em->getRepository('AppFrontBundle:Category')->findBy(array('popular' => true));
    }
    
    public function getAllVendors(){
        $em = $this->container->get('doctrine')->getManager();
        return $em->getRepository('AppFrontBundle:Vendor')->findAll();
    }
    
    public function getAllRegions(){
        $em = $this->container->get('doctrine')->getManager();
        return $em->getRepository('AppFrontBundle:Region')->findAll();
    }
    
    public function getExploredRegion(){
        $em = $this->container->get('doctrine')->getManager();
        $regions = $em->getRepository('AppFrontBundle:Region')->findAll();
        
        return $regions;
    }
    
    public function getOfferItems($offer){
        $em = $this->container->get('doctrine')->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')
            ->from('AppFrontBundle:StockEntry', 'e')
            ->leftJoin('e.offers','o')    
            ->where('o.id = :o')
            ->setParameter('o', $offer->getId());
            
        return $qb->getQuery()->getResult();
    }
    
    public function isDeliverable(StockEntry $entry){
        $em = $this->container->get('doctrine')->getManager();
        $region = $em->getRepository('AppFrontBundle:Region')->find($this->container->get('session')->get('region'));
        if($region){
            $productDeliverablility = $entry->getItem()->getProduct()->getDeliverable();
            
            switch ($productDeliverablility){
                case 0:
                    $regions = $entry->getStock()->getVendor()->getRegions();
                    foreach($regions as $r){
                        if($r->getId() == $region->getId()){
                            return true;
                        }
                    }
                    break;
                case 1:
                    return true;
                    break;
                case 2:
                    $p_regions = $entry->getItem()->getProduct()->getRegions()->toArray();
                    $v_regions = $entry->getStock()->getVendor()->getRegions()->toArray();
                    $regions = array_merge($p_regions, $v_regions);
                    foreach($regions as $r){
                        if($r->getId() == $region->getId()){
                            return true;
                        }
                    }
                    break;
            }
        }
        
        return false;
    }
}
