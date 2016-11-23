<?php

namespace App\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Entity\Stock;
use App\FrontBundle\Form\StockType;
use App\FrontBundle\Helper\FormHelper;
use App\FrontBundle\Entity\Vendor;

class StockController extends Controller
{
    /**
     * @Route("/{id}/results", name="stock_results", defaults={"id":0}, options={"expose"=true})
     */
    public function indexResultsAction(Request $request, Vendor $vendor = null)
    {
        $datatable = $this->get('app.front.datatable.stock');
        $datatable->buildDatatable(array('vendor' => $vendor));
        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);
        $qb = $query->getQuery();
        if($vendor){
            $qb->andWhere("stock.vendor = :v");
            $qb->setParameter('v', $vendor);
        }
        $query->setQuery($qb);
        return $query->getResponse();
    }
    
    /**
     * Displays a form to add an existing stock entity.
     *
     * @Route("/new", name="stock_new", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $dm = $this->getDoctrine()->getManager();
        $form = $this->createForm(new StockType(), new Stock());
        
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $stock = $form->getData();
                $stock->setVendor($this->getUser());
                $dm->persist($stock);
                $dm->flush();
                $this->get('session')->getFlashBag()->add('success', 'stock.msg.created');
                $code = FormHelper::REFRESH;
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
        $body = $this->renderView('AppFrontBundle:Stock:form.html.twig',
            array('form' => $form->createView())
        );
        
        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    /**
     * Displays a form to edit an existing stock entity.
     *
     * @Route("/{id}/edit", name="stock_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Stock $stock)
    {
        $dm = $this->getDoctrine()->getManager();
        $form = $this->createForm(new StockType(), $stock);
        
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $stock = $form->getData();
                $dm->persist($stock);
                $dm->flush();
                $this->get('session')->getFlashBag()->add('success', 'stock.msg.updated');
                $code = FormHelper::REFRESH;
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
        $body = $this->renderView('AppFrontBundle:Stock:form.html.twig',
            array('form' => $form->createView())
        );
        
        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    /**
     * Displays a form to delete an existing stock entity.
     *
     * @Route("/{id}/delete", name="stock_delete", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, Stock $stock)
    {
        $dm = $this->getDoctrine()->getManager();
        $dm->remove($stock);
        $dm->flush();
        
        $this->get('session')->getFlashBag()->add('success', 'stock.msg.removed');
        return new Response(json_encode(array('code' => FormHelper::REFRESH)));
    }
    
    /**
     * Displays a all items in the stock entity.
     *
     * @Route("/{id}/items", name="stock_items", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function itemsAction(Request $request, Stock $stock)
    {
        $itemsDatatable = $this->get('app.front.datatable.stockentry');
        $itemsDatatable->buildDatatable(array('stock' => $stock));
        return $this->render('AppFrontBundle:Stock:items.html.twig', array(
            'itemsDatatable' => $itemsDatatable,
        ));
    }
}
