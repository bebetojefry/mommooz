<?php

namespace App\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Entity\Brand;
use App\FrontBundle\Form\BrandType;
use App\FrontBundle\Helper\FormHelper;

class BrandController extends Controller
{
    /**
     * @Route("/results", name="brand_results", options={"expose"=true})
     */
    public function indexResultsAction()
    {
        $datatable = $this->get('app.front.datatable.brand');
        $datatable->buildDatatable();
        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);
        return $query->getResponse();
    }
    
    /**
     * Displays a form to add an existing location entity.
     *
     * @Route("/new", name="brand_new", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $dm = $this->getDoctrine()->getManager();
        $brand = new Brand();
        $brand->setStatus(true);
        $form = $this->createForm(new BrandType($dm), $brand);
                
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $brand = $form->getData();
                $dm->persist($brand);
                $dm->flush();
                $this->get('session')->getFlashBag()->add('success', 'brand.msg.updated');
                $code = FormHelper::REFRESH;
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
        $body = $this->renderView('AppFrontBundle:Brand:form.html.twig',
            array('form' => $form->createView())
        );
        
        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    /**
     * Displays a form to edit an existing brand entity.
     *
     * @Route("/{id}/edit", name="brand_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Brand $brand)
    {
        $dm = $this->getDoctrine()->getManager();
        $form = $this->createForm(new BrandType($dm), $brand);
        
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $brand = $form->getData();
                $dm->persist($brand);
                $dm->flush();
                $this->get('session')->getFlashBag()->add('success', 'brand.msg.created');
                $code = FormHelper::REFRESH;
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
        $keywords = $brand->getKeywords()->getValues();
        $keyword_values = array();
        foreach($keywords as $keyword){
            $keyword_values[] = array('id' => $keyword->getId(), 'name' => $keyword->getKeyword());
        }
        
        $body = $this->renderView('AppFrontBundle:Brand:form.html.twig',
            array('form' => $form->createView(), 'keyword_values' => json_encode($keyword_values))
        );
        
        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    /**
     * Displays a form to delete an existing brand entity.
     *
     * @Route("/{id}/delete", name="brand_delete", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, Brand $brand)
    {
        $dm = $this->getDoctrine()->getManager();
        $dm->remove($brand);
        $dm->flush();
        
        $this->get('session')->getFlashBag()->add('success', 'brand.msg.removed');
        return new Response(json_encode(array('code' => FormHelper::REFRESH)));
    }
    
    /**
     * List Brand products.
     *
     * @Route("/{id}/items", name="brand_show", options={"expose"=true})
     * @Method({"GET"})
     */
    public function productsAction(Request $request, Brand $brand)
    {
        $itemDatatable = $this->get('app.front.datatable.item');
        $itemDatatable->buildDatatable(array('brand' => $brand));
        return $this->render('AppFrontBundle:Admin:item.html.twig', array(
            'itemDatatable' => $itemDatatable,
        ));
    }
}
