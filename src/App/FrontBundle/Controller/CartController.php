<?php

namespace App\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Entity\Cart;
use App\FrontBundle\Helper\FormHelper;

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
     * @Route("/item/add", name="add_item_to_cart", options={"expose"=true})
     */
    public function addItemAction(Request $request, Cart $cart = null)
    {
        
    }
}
