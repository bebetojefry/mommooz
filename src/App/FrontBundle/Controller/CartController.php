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
                
                $this->get('session')->getFlashBag()->add('success', 'cart.msg.item.added');
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
        
        $this->get('session')->getFlashBag()->add('success', 'cart.msg.item.removed');
        return new Response(json_encode(array('code' => FormHelper::REFRESH)));
    }
    
    /**
     * @Route("/{id}/submit", name="submit_order", options={"expose"=true})
     */
    public function submitAction(Request $request, Cart $cart)
    {
        
    }
}
