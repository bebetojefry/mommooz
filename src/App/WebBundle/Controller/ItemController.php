<?php

namespace App\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Entity\StockEntry;
use App\FrontBundle\Entity\Item;
use App\FrontBundle\Entity\ItemView;

class ItemController extends Controller
{
    /**
     * @Route("/{id}/page", name="item_page", options={"expose"=true})
     */
    public function pageAction(StockEntry $stockEntry)
    {
        $em = $this->getDoctrine()->getManager();
        
        $view = new ItemView();
        $view->setSessionId(session_id());
        $view->setUser($this->getUser());
        $view->setEntry($stockEntry);
        
        $em->persist($view);
        $em->flush();
        
        $query = $em->getRepository('AppFrontBundle:ItemView')->createQueryBuilder('iv');
        
        if($user = $this->getUser()){
            $query->where('iv.user = :user')
            ->setParameter('user', $user);
        } else {
            $query->where('iv.sessionId = :sessionId')
            ->setParameter('sessionId', session_id());
        }
        
        $query->andWhere('iv.entry <> :entry')
            ->setParameter('entry', $stockEntry);
                
        $query->groupBy('iv.entry')->orderBy('iv.viewedOn', 'DESC')->setMaxResults(3)
        ->getQuery();

        $recently_viewed = $query->getQuery()->getResult();
        
        return $this->render('AppWebBundle:Item:index.html.twig', array(
            'entry' => $stockEntry,
            'recently_viewed' => $recently_viewed
        ));
    }
    
    /**
     * @Route("/{id}/variant", name="item_page_variant", options={"expose"=true})
     */
    public function variantAction(Request $request, Item $item)
    {
        $entries = $item->getLowCostEntries();
        $stockEntry = null;
        foreach($entries as $entry){
            if($entry->getItem()->getVariants()->count() > 0 && $entry->getVariant()->getId() == $request->query->get('variant')){
                $stockEntry = $entry;
            }
        }
        if($stockEntry){
            return $this->redirect($this->generateUrl('item_page', array('id' => $stockEntry->getId())));
        }
        
        return new Response('No Item Found...');
    }
    
    /**
     * @Route("/product/page/{id}", name="product_item_page", options={"expose"=true})
     */
    public function productitemAction(Item $item)
    {
        echo get_class($item); exit;
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
