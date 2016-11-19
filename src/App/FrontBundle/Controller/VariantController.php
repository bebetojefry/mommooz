<?php

namespace App\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Entity\VariantType;
use App\FrontBundle\Form\VariantTypeType;
use App\FrontBundle\Helper\FormHelper;

class VariantController extends Controller
{
    /**
     * @Route("/results", name="varianttype_results")
     */
    public function indexResultsAction()
    {
        $datatable = $this->get('app.front.datatable.variant');
        $datatable->buildDatatable();
        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);
        return $query->getResponse();
    }
    
    /**
     * Displays a form to add an existing variant entity.
     *
     * @Route("/new", name="varianttype_new", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $dm = $this->getDoctrine()->getManager();
        $form = $this->createForm(new VariantTypeType(), new VariantType());
        
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $variant = $form->getData();
                $dm->persist($variant);
                $dm->flush();
                $this->get('session')->getFlashBag()->add('success', 'variant.msg.created');
                $code = FormHelper::REFRESH;
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
        $body = $this->renderView('AppFrontBundle:Variant:form.html.twig',
            array('form' => $form->createView())
        );
        
        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    /**
     * Displays a form to edit an existing variant entity.
     *
     * @Route("/{id}/edit", name="varianttype_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, VariantType $variant)
    {
        $dm = $this->getDoctrine()->getManager();
        $form = $this->createForm(new VariantTypeType(), $variant);
        
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $variant = $form->getData();
                $dm->persist($variant);
                $dm->flush();
                $this->get('session')->getFlashBag()->add('success', 'variant.msg.updated');
                $code = FormHelper::REFRESH;
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
        $body = $this->renderView('AppFrontBundle:Variant:form.html.twig',
            array('form' => $form->createView())
        );
        
        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    /**
     * Displays a form to delete an existing variant entity.
     *
     * @Route("/{id}/delete", name="varianttype_delete", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, VariantType $variant)
    {
        $dm = $this->getDoctrine()->getManager();
        $dm->remove($variant);
        $dm->flush();
        
        $this->get('session')->getFlashBag()->add('success', 'variant.msg.removed');
        return new Response(json_encode(array('code' => FormHelper::REFRESH)));
    }
}
