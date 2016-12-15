<?php

namespace App\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Entity\StockEntry;

class ItemController extends Controller
{
    /**
     * @Route("/page/{id}", name="item_page", options={"expose"=true})
     */
    public function pageAction(StockEntry $stockEntry)
    {
        echo get_class($stockEntry); exit;
    }
    
    public function mostPurchasedAction(){
        $em = $this->getDoctrine()->getManager();
        $dql =  "SELECT sp.id AS id, SUM(sp.quantity) AS purchased FROM App\FrontBundle\Entity\StockPurchase sp " .
                "WHERE sp.reverse = ?1 GROUP BY sp.stockItem ORDER BY purchased DESC";
        
        $result = $em->createQuery($dql)
                ->setParameter(1, false)
                ->setMaxResults(3)
                ->getResult();
        
        $items = array();
        $repo = $em->getRepository('AppFrontBundle:StockPurchase');
        foreach($result as $res){
            $purchase = $repo->find($res['id']);
            $items[] = $purchase->getStockItem();
        }
        
        return $this->render('AppWebBundle:Item:mostPurchased.html.twig', array(
            'items' => $items
        ));
    }
    
    public function newAction() {
        $items = $this->getDoctrine()->getManager()->getRepository('AppFrontBundle:StockEntry')->findBy(array('status' => true), array(), 4, 0);
        return $this->render('AppWebBundle:Item:new.html.twig', array(
            'items' => $items
        ));
    }
}
