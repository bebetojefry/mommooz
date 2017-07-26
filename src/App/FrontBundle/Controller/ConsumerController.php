<?php

namespace App\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ConsumerController extends Controller
{
    /**
     * @Route("/results", name="consumer_results")
     */
    public function indexResultsAction()
    {
        $datatable = $this->get('app.front.datatable.consumer');
        $datatable->buildDatatable();
        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);
        return $query->getResponse();
    }
    
    /**
     * Displays a form to add an existing consumer entity.
     *
     * @Route("/new", name="consumer_new", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $dm = $this->getDoctrine()->getManager();
        $vendor = new Vendor();
        $vendor->addAddress(new Address());
        $form = $this->createForm(new UserType($dm, $this->get('router')), $vendor);
        
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $vendor = $form->getData();
                $username = $vendor->getEmail();
                $password = 'VEND'.time();
                $vendor->setUsername($username);
                $vendor->setPassword($password);
                $vendor->setLocale('en');      
                $dm->persist($vendor);
                $dm->flush();
                
                $stock = new Stock();
                $stock->setName('My Stock');
                $stock->setVendor($vendor);
                $stock->setDate(new \DateTime('now'));
                $stock->setStatus(TRUE);
                $dm->persist($stock);
                $dm->flush();
                
                $this->get('session')->getFlashBag()->add('success', 'vendor.msg.created');
                $code = FormHelper::REFRESH;
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
       
        $body = $this->renderView('AppFrontBundle:Vendor:form.html.twig',
            array('form' => $form->createView())
        );
        
        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    /**
     * Displays a form to edit an existing consumer entity.
     *
     * @Route("/{id}/edit", name="consumer_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Vendor $vendor)
    {
        $dm = $this->getDoctrine()->getManager();
        if($vendor->getAddresses()->count() == 0 && $request->isMethod('GET')){
            $vendor->addAddress(new Address());
        }
        
        $form = $this->createForm(new UserType($dm, $this->get('router')), $vendor);
        
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $vendor = $form->getData();
                $vendor->setUsername($vendor->getEmail());
                $dm->persist($vendor);
                $dm->flush();
                $this->get('session')->getFlashBag()->add('success', 'vendor.msg.updated');
                $code = FormHelper::REFRESH;
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
        $regions = $vendor->getRegions()->getValues();
        $region_values = array();
        foreach($regions as $region){
            $region_values[] = array('id' => $region->getId(), 'name' => $region->getRegionName().' ('.$region->getDistrict()->getName().')');
        }
        
        $body = $this->renderView('AppFrontBundle:Vendor:form.html.twig',
            array('form' => $form->createView(), 'region_values' => json_encode($region_values))
        );
        
        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    /**
     * Displays a form to delete an existing consumer entity.
     *
     * @Route("/{id}/delete", name="consumer_delete", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, Vendor $vendor)
    {
        $dm = $this->getDoctrine()->getManager();
        $dm->remove($vendor);
        $dm->flush();
        
        $this->get('session')->getFlashBag()->add('success', 'vendor.msg.removed');
        return new Response(json_encode(array('code' => FormHelper::REFRESH)));
    }
}
