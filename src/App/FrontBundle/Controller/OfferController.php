<?php

namespace App\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Entity\Offer;
use App\FrontBundle\Form\OfferType;
use App\FrontBundle\Helper\FormHelper;
use Symfony\Component\HttpFoundation\JsonResponse;

class OfferController extends Controller
{
    /**
     * @Route("/results", name="offer_results")
     */
    public function indexResultsAction()
    {
        $datatable = $this->get('app.front.datatable.offer');
        $datatable->buildDatatable();
        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);
        return $query->getResponse();
    }
    
   
    /**
     * Displays a form to add an existing offer entity.
     *
     * @Route("/new", name="offer_new", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $dm = $this->getDoctrine()->getManager();
        $form = $this->createForm(new OfferType($dm), new Offer());
        
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $offer = $form->getData();
                $dm->persist($offer);
                $dm->flush();
                $code = FormHelper::REFRESH;
                $this->get('session')->getFlashBag()->add('success', 'offer.msg.created');
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
        $body = $this->renderView('AppFrontBundle:Offer:form.html.twig',
            array('form' => $form->createView())
        );
        
        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    /**
     * Displays a form to edit an existing offer entity.
     *
     * @Route("/{id}/edit", name="offer_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Offer $offer)
    {
        $dm = $this->getDoctrine()->getManager();
        $form = $this->createForm(new OfferType($dm), $offer);
        
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $offer = $form->getData();
                $dm->persist($offer);
                $dm->flush();
                $code = FormHelper::REFRESH;
                $this->get('session')->getFlashBag()->add('success', 'offer.msg.updated');
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
        $body = $this->renderView('AppFrontBundle:Offer:form.html.twig',
            array('form' => $form->createView())
        );
        
        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    /**
     * Displays a form to delete an existing offer entity.
     *
     * @Route("/{id}/delete", name="offer_delete", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, Offer $offer)
    {
        $dm = $this->getDoctrine()->getManager();
        $dm->remove($offer);
        $dm->flush();
        
        $this->get('session')->getFlashBag()->add('success', 'offer.msg.removed');
        return new Response(json_encode(array('code' => FormHelper::REFRESH)));
    }
    
    /**
     * @Route("/search", name="offer_search")
     */
    public function searchAction(Request $request){
        $q = $request->query->get('q');
        $offers = $this->getDoctrine()->getManager()->getRepository("AppFrontBundle:Offer")->createQueryBuilder('o')
        ->where('o.name LIKE :q')
        ->setParameter('q', '%'.$q.'%')
        ->getQuery()
        ->getResult();
        
        $result = array();
        foreach($offers as $offer){
            $result[] = array('id' => $offer->getId(), 'name' => $offer->getName());
        }
        
        return new JsonResponse($result);
    }
}
