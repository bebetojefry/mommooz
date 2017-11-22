<?php

namespace App\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Entity\DeliveryCharge;
use App\FrontBundle\Form\DeliveryChargeType;
use App\FrontBundle\Helper\FormHelper;

class DeliveryController extends Controller
{
    /**
     * @Route("/results", name="delivery_charge_results", options={"expose"=true})
     */
    public function indexResultsAction()
    {
        $datatable = $this->get('app.front.datatable.delivery_charge');
        $datatable->buildDatatable();
        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);
        return $query->getResponse();
    }
    
    /**
     * Displays a form to add an existing location entity.
     *
     * @Route("/new", name="delivery_charge_new", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $dm = $this->getDoctrine()->getManager();
        $charge = new DeliveryCharge();
        $form = $this->createForm(new DeliveryChargeType($dm), $charge);
                
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $charge = $form->getData();
                $dm->persist($charge);
                $dm->flush();
                $this->get('session')->getFlashBag()->add('success', 'New delivery charge added');
                $code = FormHelper::REFRESH;
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
        $body = $this->renderView('AppFrontBundle:DeliveryCharge:form.html.twig',
            array('form' => $form->createView())
        );
        
        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    /**
     * Displays a form to edit an existing brand entity.
     *
     * @Route("/{id}/edit", name="delivery_charge_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, DeliveryCharge $charge)
    {
        $dm = $this->getDoctrine()->getManager();
        $form = $this->createForm(new DeliveryChargeType($dm), $charge);
        
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $charge = $form->getData();
                $dm->persist($charge);
                $dm->flush();
                $this->get('session')->getFlashBag()->add('success', 'Delievry charge updated');
                $code = FormHelper::REFRESH;
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
        $body = $this->renderView('AppFrontBundle:DeliveryCharge:form.html.twig',
            array('form' => $form->createView())
        );
        
        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    /**
     * Displays a form to delete an existing brand entity.
     *
     * @Route("/{id}/delete", name="delivery_charge_delete", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, DeliveryCharge $charge)
    {
        $dm = $this->getDoctrine()->getManager();
        $dm->remove($charge);
        $dm->flush();
        $this->get('session')->getFlashBag()->add('success', 'Delivery charge removed');
        
        return new Response(json_encode(array('code' => FormHelper::REFRESH)));
    }
}
