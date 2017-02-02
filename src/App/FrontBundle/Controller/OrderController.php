<?php

namespace App\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Helper\FormHelper;
use App\FrontBundle\Entity\Purchase;
use App\FrontBundle\Form\PurchaseType;

class OrderController extends Controller
{
    /**
     * @Route("/results", name="purchase_results")
     */
    public function indexResultsAction()
    {
        $datatable = $this->get('app.front.datatable.purchase');
        $datatable->buildDatatable();
        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);
        return $query->getResponse();
    }
    
    /**
     * Displays a all items in the purchase entity.
     *
     * @Route("/{id}/items", name="purchase_show", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function itemsAction(Request $request, Purchase $purchase)
    {
        $itemsDatatable = $this->get('app.front.datatable.purchaseitem');
        $itemsDatatable->buildDatatable(array('purchase' => $purchase));
        return $this->render('AppFrontBundle:Order:items.html.twig', array(
            'itemsDatatable' => $itemsDatatable,
            'purchase' => $purchase
        ));
    }
    
    /**
     * Change status of a purchase entity.
     *
     * @Route("/{id}/status", name="change_status", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function statusAction(Request $request, Purchase $purchase)
    {
        $dm = $this->getDoctrine()->getManager();
        $form = $this->createForm(new PurchaseType(), $purchase);
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $purchase = $form->getData();
                $dm->persist($purchase);
                $dm->flush();
                $this->get('session')->getFlashBag()->add('success', 'purchase.msg.updated');
                $code = FormHelper::REFRESH;
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
        $body = $this->renderView('AppFrontBundle:Order:form.html.twig',
            array('form' => $form->createView())
        );

        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    /**
     * Clear a all stock items in the purchase entity.
     *
     * @Route("/{id}/items/clear", name="purchase_clear", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function clearAction(Request $request, Purchase $purchase)
    {
        $dm = $this->getDoctrine()->getManager();
        $stockPurchases = $dm->getRepository("AppFrontBundle:StockPurchase")->createQueryBuilder('sp')
        ->where('sp.purchase = :p')
        ->setParameter('p', $purchase)
        ->getQuery()
        ->getResult();
        
        foreach($stockPurchases as $stockPurchase){
            $dm->remove($stockPurchase);
        }
        
        $dm->flush();
        
        $this->get('session')->getFlashBag()->add('success', 'purchase.msg.cleared');
        return new Response(json_encode(array('code' => FormHelper::REFRESH)));
    }
}
