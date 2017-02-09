<?php

namespace App\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Entity\Stock;
use App\FrontBundle\Entity\StockEntry;
use App\FrontBundle\Form\StockEntryType;
use App\FrontBundle\Helper\FormHelper;
use App\FrontBundle\Entity\Item;
use Doctrine\ORM\QueryBuilder;
use App\FrontBundle\Entity\StockPurchase;
use App\FrontBundle\Form\AdminStockEntryType;

class StockEntryController extends Controller
{
    /**
     * @Route("/{id}/results", name="stockentry_results", defaults={"id":0}, options={"expose"=true})
     */
    public function indexResultsAction(Request $request, Stock $stock = null)
    {
        $datatable = $this->get('app.front.datatable.stockentry');
        $datatable->buildDatatable(array('stock' => $stock));
        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);
        $query->buildQuery();
        $qb = $query->getQuery();
        if($stock){
            $qb->andWhere("stock_entry.stock = :s");
            $qb->setParameter('s', $stock);
        }
        $query->setQuery($qb);
        return $query->getResponse(false);
    }
    
    /**
     * @Route("/{id}/admin/results", name="admin_stockentry_results", defaults={"id":0}, options={"expose"=true})
     */
    public function adminResultsAction(Request $request, Stock $stock = null)
    {
        $datatable = $this->get('app.front.datatable.adminstockentry');
        $datatable->buildDatatable(array('stock' => $stock));
        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);
        $query->buildQuery();
        $qb = $query->getQuery();
        if($stock){
            $qb->andWhere("stock_entry.stock = :s");
            $qb->setParameter('s', $stock);
        }
        $query->setQuery($qb);
        return $query->getResponse(false);
    }
    
    /**
     * Displays a form to add an existing stockentry entity.
     *
     * @Route("/{id}/new", name="stockentry_new", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Stock $stock = null)
    {
        $dm = $this->getDoctrine()->getManager();
        $item = null;
        if(isset($_POST['app_frontbundle_stockentry']['state']) && $_POST['app_frontbundle_stockentry']['state']){
            $item = $dm->getRepository('AppFrontBundle:Item')->find($_POST['app_frontbundle_stockentry']['state']);
        }
        
        $entry = new StockEntry();
        $entry->setStock($stock);
        $form = $this->createForm(new StockEntryType($dm, $this->getUser(), $item), $entry);
        
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $stockentry = $form->getData();
                if($stockentry->getState() == null) {
                    $stockentry->setPrice(0);
                    $stockentry->setState($stockentry->getItem()->getId());
                    $stockentry->setCommtype($stockentry->getItem()->getCommType());
                    $stockentry->setCommvalue($stockentry->getItem()->getCommValue());
                    $form = $this->createForm(new StockEntryType($dm, $this->getUser(), $stockentry->getItem()), $stockentry);
                    $code = FormHelper::REFRESH_FORM;
                } else {
                    $dm->persist($stockentry);
                    $dm->flush();
                    $this->get('session')->getFlashBag()->add('success', 'stockentry.msg.created');
                    $code = FormHelper::REFRESH;
                }
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
        $body = $this->renderView('AppFrontBundle:Stock:entry.html.twig',
            array('form' => $form->createView())
        );
        
        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    /**
     * Displays a form to edit an existing stockentry entity.
     *
     * @Route("/{id}/edit", name="stockentry_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, StockEntry $stockentry)
    {
        $dm = $this->getDoctrine()->getManager();
        $stockentry->setState($stockentry->getItem()->getId());
        $stockentry->setCommtype($stockentry->getItem()->getCommType());
        $stockentry->setCommvalue($stockentry->getItem()->getCommValue());
        $form = $this->createForm(new StockEntryType($dm, $this->getUser(), $stockentry->getItem()), $stockentry);
        
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $stockentry = $form->getData();
                $dm->persist($stockentry);
                $dm->flush();
                $this->get('session')->getFlashBag()->add('success', 'stockentry.msg.updated');
                $code = FormHelper::REFRESH;
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
        $keywords = $stockentry->getKeywords()->getValues();
        $keyword_values = array();
        foreach($keywords as $keyword){
            $keyword_values[] = array('id' => $keyword->getId(), 'name' => $keyword->getKeyword());
        }
        
        $offers = $stockentry->getOffers()->getValues();
        $offer_values = array();
        foreach($offers as $offer){
            $offer_values[] = array('id' => $offer->getId(), 'name' => $offer->getName());
        }
        
        $body = $this->renderView('AppFrontBundle:Stock:entry.html.twig',
            array('form' => $form->createView(), 'keyword_values' => json_encode($keyword_values), 'offer_values' => json_encode($offer_values))
        );
        
        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    /**
     * Displays a admin form to edit an existing stockentry entity.
     *
     * @Route("/{id}/manage", name="stockentry_manage", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function manageAction(Request $request, StockEntry $stockentry)
    {
        $dm = $this->getDoctrine()->getManager();

        $form = $this->createForm(new AdminStockEntryType($dm, $stockentry->getItem()), $stockentry);
        
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $stockentry = $form->getData();
                $dm->persist($stockentry);
                $dm->flush();
                $this->get('session')->getFlashBag()->add('success', 'stockentry.msg.updated');
                $code = FormHelper::REFRESH;
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
        $offers = $stockentry->getOffers()->getValues();
        $offer_values = array();
        foreach($offers as $offer){
            $offer_values[] = array('id' => $offer->getId(), 'name' => $offer->getName());
        }
        
        $body = $this->renderView('AppFrontBundle:Stock:adminEntry.html.twig',
            array('form' => $form->createView(), 'offer_values' => json_encode($offer_values))
        );
        
        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    /**
     * Displays a form to delete an existing stockentry entity.
     *
     * @Route("/{id}/delete", name="stockentry_delete", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, StockEntry $stockentry)
    {
        $dm = $this->getDoctrine()->getManager();
        $dm->remove($stockentry);
        $dm->flush();
        
        $this->get('session')->getFlashBag()->add('success', 'stockentry.msg.removed');
        return new Response(json_encode(array('code' => FormHelper::REFRESH)));
    }
    
    /**
     * Displays a form to add stock to an existing stockentry entity.
     *
     * @Route("/{id}/add", name="stockentry_add", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function addAction(Request $request, StockEntry $stockentry)
    {
        $em = $this->getDoctrine()->getManager();
        $val = $request->get('val');
        
        if(is_numeric($val)){
            $quantity = $stockentry->getQuantity() + $val;
            $stockentry->setQuantity($quantity);
            $em->persist($stockentry);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'stockentry.msg.added');
        } else {
             $this->get('session')->getFlashBag()->add('error', 'stockentry.msg.invalid_quantity');
        }
        
        return new Response(json_encode(array('code' => FormHelper::REFRESH)));
    }
    
    /**
     * Displays a form to minus stock to an existing stockentry entity.
     *
     * @Route("/{id}/minus", name="stockentry_minus", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function minusAction(Request $request, StockEntry $stockentry)
    {
        $em = $this->getDoctrine()->getManager();
        $val = $request->get('val');
        if(is_numeric($val)){
            if($stockentry->getInStock() >= $val){
                $purchase = new StockPurchase();
                $purchase->setUser($stockentry->getStock()->getVendor());
                $purchase->setPrice($stockentry->getActualPrice());
                $purchase->setQuantity($val);
                $purchase->setReverse(TRUE);
                $purchase->setStockItem($stockentry);
                $purchase->setPurchsedOn(new \DateTime('now'));
                $em->persist($purchase);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'stockentry.msg.minus');
            } else {
                $this->get('session')->getFlashBag()->add('error', 'stockentry.msg.stock_is_less');
            }
        } else {
             $this->get('session')->getFlashBag()->add('error', 'stockentry.msg.invalid_quantity');
        }
        
        return new Response(json_encode(array('code' => FormHelper::REFRESH)));
    }
    
    /**
     * Displays a form to change price of an existing stockentry entity.
     *
     * @Route("/{id}/price", name="stockentry_price", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function priceAction(Request $request, StockEntry $stockentry)
    {
        $em = $this->getDoctrine()->getManager();
        $price = $request->get('val');
        if(is_numeric($price)){
            if($stockentry->getItem()->getCommType() == 1){
                $actualPrice = $price + $stockentry->getItem()->getCommValue();
            } else {
                $actualPrice = $price + round(($price * $stockentry->getItem()->getCommValue())/100, 2);
            }
            
            $stockentry->setPrice($price);
            $stockentry->setActualPrice($actualPrice);
            $em->persist($stockentry);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'stockentry.msg.price');
        } else {
             $this->get('session')->getFlashBag()->add('error', 'stockentry.msg.invalid_price');
        }
        
        return new Response(json_encode(array('code' => FormHelper::REFRESH)));
    }
}
