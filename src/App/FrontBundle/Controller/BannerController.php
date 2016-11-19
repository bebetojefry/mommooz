<?php

namespace App\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Entity\Banner;
use App\FrontBundle\Entity\CategoryBanner;
use App\FrontBundle\Entity\ItemBanner;
use App\FrontBundle\Entity\OfferBanner;
use App\FrontBundle\Form\CategoryBannerType;
use App\FrontBundle\Form\ItemBannerType;
use App\FrontBundle\Form\OfferBannerType;
use App\FrontBundle\Helper\FormHelper;

class BannerController extends Controller
{
    /**
     * @Route("/results", name="banner_results")
     */
    public function indexResultsAction()
    {
        $datatable = $this->get('app.front.datatable.banner');
        $datatable->buildDatatable();
        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);
        return $query->getResponse();
    }
    
    /**
     * Displays a form to add an existing banner entity.
     *
     * @Route("/new", name="banner_new", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $dm = $this->getDoctrine()->getManager();
        switch($request->query->get('type')){
            case 'category':
                $type = new CategoryBannerType($dm);
                $entity = new CategoryBanner();
                break;
            case 'item':
                $type = new ItemBannerType($dm);
                $entity = new ItemBanner();
                break;
            case 'offer':
                $type = new OfferBannerType($dm);
                $entity = new OfferBanner();
                break;
        }
        $form = $this->createForm($type, $entity);
        
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $banner = $form->getData();
                $dm->persist($banner);
                $dm->flush();
                $this->get('session')->getFlashBag()->add('success', 'banner.msg.created');
                $code = FormHelper::REFRESH;
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
        $body = $this->renderView('AppFrontBundle:Banner:form.html.twig',
            array('form' => $form->createView())
        );
        
        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    /**
     * Displays a form to edit an existing Banner entity.
     *
     * @Route("/{id}/edit", name="banner_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Banner $banner)
    {
        $dm = $this->getDoctrine()->getManager();
        $arr = explode('\\', get_class($banner));
        $class = 'App\FrontBundle\Form\\'.end($arr).'Type';
        $type = new $class($dm);
        
        $form = $this->createForm($type, $banner);
        
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $banner = $form->getData();
                $dm->persist($banner);
                $dm->flush();
                $this->get('session')->getFlashBag()->add('success', 'banner.msg.updated');
                $code = FormHelper::REFRESH;
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
        $body = $this->renderView('AppFrontBundle:Banner:form.html.twig',
            array('form' => $form->createView())
        );
        
        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    /**
     * Displays a form to delete an existing Banner entity.
     *
     * @Route("/{id}/delete", name="banner_delete", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, Banner $banner)
    {
        $dm = $this->getDoctrine()->getManager();
        $dm->remove($banner);
        $dm->flush();
        
        $this->get('session')->getFlashBag()->add('success', 'banner.msg.removed');
        return new Response(json_encode(array('code' => FormHelper::REFRESH)));
    }
}
