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
     * Displays a form to add an existing stockentry entity.
     *
     * @Route("/{id}/new", name="stockentry_new", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Stock $stock = null)
    {
        $dm = $this->getDoctrine()->getManager();
        
        $item = $this->get('session')->get('stock_entry_item');
        if($item instanceof Item){
            $this->get('session')->remove('stock_entry_item');
        }
        
        $entry = new StockEntry();
        $entry->setStock($stock);
        $form = $this->createForm(new StockEntryType($item), $entry);
        
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $stockentry = $form->getData();
                if($stockentry->getState() == null) {
                    $stockentry->setState(1);
                    $this->get('session')->set('stock_entry_item', $stockentry->getItem());
                    $form = $this->createForm(new StockEntryType($this->get('session')->get('stock_entry_item')), $stockentry);
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
        $form = $this->createForm(new StockEntryType($stockentry->getItem()), $stockentry);
        
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
        
        $body = $this->renderView('AppFrontBundle:Stock:entry.html.twig',
            array('form' => $form->createView())
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
}
