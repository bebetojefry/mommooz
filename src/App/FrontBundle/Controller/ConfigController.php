<?php

namespace App\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Entity\Config;
use App\FrontBundle\Form\ConfigType;
use App\FrontBundle\Helper\FormHelper;

class ConfigController extends Controller
{
    /**
     * @Route("/results", name="config_results")
     */
    public function indexResultsAction()
    {
        $datatable = $this->get('app.front.datatable.config');
        $datatable->buildDatatable();
        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);
        return $query->getResponse();
    }
    
   
    /**
     * Displays a form to add an existing config entity.
     *
     * @Route("/new", name="config_new", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $dm = $this->getDoctrine()->getManager();
        $form = $this->createForm(new ConfigType(), new Config());
        
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $config = $form->getData();
                $dm->persist($config);
                $dm->flush();
                $code = FormHelper::REFRESH;
                $this->get('session')->getFlashBag()->add('success', 'config.msg.created');
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
        $body = $this->renderView('AppFrontBundle:Config:form.html.twig',
            array('form' => $form->createView())
        );
        
        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    /**
     * Displays a form to edit an existing config entity.
     *
     * @Route("/{id}/edit", name="config_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Config $config)
    {
        $dm = $this->getDoctrine()->getManager();
        $form = $this->createForm(new ConfigType(), $config);
        
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $config = $form->getData();
                $dm->persist($config);
                $dm->flush();
                $code = FormHelper::REFRESH;
                $this->get('session')->getFlashBag()->add('success', 'config.msg.updated');
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
        $body = $this->renderView('AppFrontBundle:Config:form.html.twig',
            array('form' => $form->createView())
        );
        
        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    /**
     * Displays a form to delete an existing config entity.
     *
     * @Route("/{id}/delete", name="config_delete", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, Config $config)
    {
        $dm = $this->getDoctrine()->getManager();
        $dm->remove($config);
        $dm->flush();
        
        $this->get('session')->getFlashBag()->add('success', 'config.msg.removed');
        return new Response(json_encode(array('code' => FormHelper::REFRESH)));
    }
}
