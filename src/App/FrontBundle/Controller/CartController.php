<?php

namespace App\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Entity\Cart;
use App\FrontBundle\Helper\FormHelper;
use App\FrontBundle\Entity\CartItem;
use App\FrontBundle\Entity\Purchase;
use App\FrontBundle\Entity\PurchaseItem;
use App\FrontBundle\Entity\StockPurchase;
use App\FrontBundle\Entity\Reward;
use App\FrontBundle\Entity\RewardUse;

class CartController extends Controller
{
    /**
     * @Route("/{id}/results", name="cart_results", options={"expose"=true})
     */
    public function indexResultsAction(Request $request, Cart $cart = null)
    {
        $datatable = $this->get('app.front.datatable.cartitem');
        $datatable->buildDatatable(array('cart' => $cart));
        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);
        $query->buildQuery();
        $qb = $query->getQuery();
        if($cart){
            $qb->andWhere("cart_item.cart = :c");
            $qb->setParameter('c', $cart);
        }
        
        $query->setQuery($qb);
        return $query->getResponse(false);
    }
    
    /**
     * @Route("/{id}/item/add", name="add_item_to_cart", options={"expose"=true})
     */
    public function addItemAction(Request $request, Cart $cart = null)
    {
        $dm = $this->getDoctrine()->getManager();
        
        if($request->isMethod('POST')){
            if(isset($_POST['vendor_opt_category'])){
                $category = $dm->getRepository('AppFrontBundle:Category')->find($_POST['vendor_opt_category']);
                if($category){
                    $body = $this->renderView('AppFrontBundle:Cart:cat_items.html.twig',
                        array('category' => $category)
                    );

                    return new Response(json_encode(array('code' => FormHelper::REFRESH_FORM, 'data' => $body)));
                }
            } else if(isset($_POST['entries'])){
                foreach($_POST['entries'] as $it){
                    $entry = $dm->getRepository('AppFrontBundle:StockEntry')->find($it);
                    $qty = 1;
                    if($item = $cart->inCart($entry)){
                        $item->setQuantity($item->getQuantity() + $qty);
                    } else {
                        $item = new CartItem();
                        $item->setCart($cart);
                        $item->setEntry($entry);
                        $item->setQuantity($qty);
                        $item->setPrice($entry->getActualPrice());
                        $item->setStatus(false);
                    }

                    if($entry->getInStock() >= $item->getQuantity()){
                        $dm->persist($item);
                    }
                }
                
                $dm->flush();
                
                $this->get('session')->getFlashBag()->add('success', 'Item added to cart');
                return new Response(json_encode(array('code' => FormHelper::REFRESH, 'data' => '')));
            }
        }
        
        $rootCategory = $dm->getRepository('AppFrontBundle:Category')->find(1);
        $resultTree = array();
        $this->getCatTree($resultTree, $rootCategory);
        
        $body = $this->renderView('AppFrontBundle:Cart:choose_cat.html.twig',
            array('treeData' => json_encode($resultTree))
        );

        return new Response(json_encode(array('code' => FormHelper::FORM, 'data' => $body)));
    }
    
    private function getCatTree(&$result, $category, $selCat = null){
        $result[] = array('text' => $category->getCategoryName(), 'id' => $category->getId());
        
        $keys = array_keys($result);
        if($selCat){
            $result[end($keys)]['state'] = array();
            if($category->hasChild($selCat)){
                $result[end($keys)]['state']['expanded'] = 'true'; 
            }
            
            if($category->getId() == $selCat->getId()){
                $result[end($keys)]['state']['selected'] = 'true'; 
            }
        }
        
        if(count($category->getChilds()) > 0){
            $result[end($keys)]['nodes'] = array();
            foreach($category->getChilds() as $cat){
                $this->getCatTree($result[end($keys)]['nodes'], $cat, $selCat);
            }
        }
    }
    
    /**
     * @Route("/item/{id}/remove", name="cart_item_remove", options={"expose"=true})
     */
    public function deleteAction(Request $request, CartItem $item)
    {
        $dm = $this->getDoctrine()->getManager();
        $dm->remove($item);
        $dm->flush();
        
        $this->get('session')->getFlashBag()->add('success', 'Item removed from cart');
        return new Response(json_encode(array('code' => FormHelper::REFRESH)));
    }
    
    /**
     * @Route("/{id}/submit", name="submit_order", options={"expose"=true})
     */
    public function submitAction(Request $request, Cart $cart)
    {
        $em = $this->getDoctrine()->getManager();
        
        if($request->isMethod('POST')){
            $address = $em->getRepository('AppFrontBundle:Address')->find($_POST['address']);

            $purchase = new Purchase();
            $purchase->setConsumer($cart->getUser());
            $purchase->setDeliverTo($address);
            $purchase->setPurchasedOn(new \DateTime('now'));
            $purchase->setMethod('COD');
            $purchase->setStatus(0);

            $em->persist($purchase);
            $em->flush();

            $total_amt = 0;
            foreach($cart->getItems() as $item){
                //create purchase item entry
                $purchaserItem = new PurchaseItem();
                $purchaserItem->setPurchase($purchase);
                $purchaserItem->setEntry($item->getEntry());
                $purchaserItem->setQuantity($item->getQuantity());
                $purchaserItem->setStatus(0);
                $purchaserItem->setUnitPrice($item->getPrice());
                $purchaserItem->setPrice($item->getQuantity()*$item->getPrice());
                $em->persist($purchaserItem);

                $total_amt += $item->getQuantity()*$item->getPrice();

                // create stock purchase history
                $stock_purchase = new StockPurchase();
                $stock_purchase->setUser($cart->getUser());
                $stock_purchase->setPurchase($purchase);
                $stock_purchase->setPrice($item->getPrice());
                $stock_purchase->setQuantity($item->getQuantity());
                $stock_purchase->setReverse(FALSE);
                $stock_purchase->setStockItem($item->getEntry());
                $stock_purchase->setPurchsedOn(new \DateTime('now'));
                $em->persist($stock_purchase);

                $em->remove($item);
            }

            if(isset($_POST['use_reward'])){
                $reward = new Reward();
                $reward->setConsumer($cart->getUser());
                $reward->setPoint($_POST['reward_points_used']*-1);
                $reward->setSource(Reward::PURCHASE);
                $reward->setCreditedOn(new \DateTime('now'));

                $em->persist($reward);

                $reward_use = new RewardUse();
                $reward_use->setConsumer($cart->getUser());
                $reward_use->setPurchase($purchase);
                $reward_use->setPoints($_POST['reward_points_used']);
                $reward_use->setMoney($_POST['reward_money']);

                $em->persist($reward_use);
            }

            // configure reward
            $reward_config = $em->getRepository('AppFrontBundle:Config')->findOneByName('purchase_reward');
            if($reward_config){
                $val = $reward_config->getValue();
                if($val > 0){
                    $points = round($total_amt/$val, 2);

                    $reward = new Reward();
                    $reward->setConsumer($cart->getUser());
                    $reward->setPoint($points);
                    $reward->setSource(Reward::PURCHASE);
                    $reward->setCreditedOn(new \DateTime('now'));

                    $em->persist($reward);
                }
            }

            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Order submitted');
            return new Response(json_encode(array('code' => FormHelper::REDIRECT, 'data' => '', 'url' => $this->generateUrl('purchase_show', array('id' => $purchase->getId())))));
        }
        
        $reward_money_config = $em->getRepository('AppFrontBundle:Config')->findOneByName('reward_money');
        $reward_money = 0;
        $total_rewards = 0;
        
        if($reward_money_config) {
            $cart_price = $cart->getPrice();
            $max_points_needed = round($cart_price*$reward_money_config->getValue(), 2);
            $rewards = $em->getRepository('AppFrontBundle:Reward')->findByConsumer($cart->getUser());
            foreach($rewards as $reward){
                $total_rewards += $reward->getPoint();
                if($total_rewards > $max_points_needed){
                    $total_rewards = $max_points_needed;
                    break;
                }
            }
            
            if($reward_money_config->getValue() > 0){
                $reward_money = round($total_rewards/$reward_money_config->getValue(), 2);
            }
        }
        
        $body = $this->renderView('AppFrontBundle:Cart:submit.html.twig',
            array(
                'cart' => $cart,
                'addresses' => $cart->getUser()->getAddresses(),
                'user' => $cart->getUser(),
                'reward_money' => $reward_money, 
                'total_rewards' => $total_rewards
            )
        );

        return new Response(json_encode(array('code' => FormHelper::FORM, 'data' => $body)));
    }
}
