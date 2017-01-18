<?php

namespace App\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Entity\Region;
use App\FrontBundle\Entity\Location;
use App\FrontBundle\Form\LocationType;
use App\FrontBundle\Helper\FormHelper;

class LocationController extends Controller
{
    /**
     * @Route("/{id}/results", name="location_results", options={"expose"=true})
     */
    public function indexResultsAction(Region $region)
    {
        $datatable = $this->get('app.front.datatable.location');
        $datatable->buildDatatable(array('region' => $region));
        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);
        $qb = $query->getQuery();
        $qb->andWhere("location.region = :r");
        $qb->setParameter('r', $region);
        $query->setQuery($qb);
        return $query->getResponse();
    }
    
    /**
     * Displays a form to add an existing location entity.
     *
     * @Route("/new/{id}", name="location_new", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Region $region)
    {
        $dm = $this->getDoctrine()->getManager();
        $location = new Location();
        $location->setRegion($region);
        $form = $this->createForm(new LocationType(), $location);
        
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $location = $form->getData();
                $location->setStatus(true);
                $dm->persist($location);
                $dm->flush();
                $this->get('session')->getFlashBag()->add('success', 'location.msg.created');
                $code = FormHelper::REFRESH;
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
        $body = $this->renderView('AppFrontBundle:Location:form.html.twig',
            array('form' => $form->createView())
        );
        
        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    /**
     * Displays a form to edit an existing location entity.
     *
     * @Route("/{id}/edit", name="location_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Location $location)
    {
        $dm = $this->getDoctrine()->getManager();
        $form = $this->createForm(new LocationType(), $location);
        
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $location = $form->getData();
                $dm->persist($location);
                $dm->flush();
                $this->get('session')->getFlashBag()->add('success', 'location.msg.created');
                $code = FormHelper::REFRESH;
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
        $body = $this->renderView('AppFrontBundle:Location:form.html.twig',
            array('form' => $form->createView())
        );
        
        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    /**
     * Displays a form to delete an existing location entity.
     *
     * @Route("/{id}/delete", name="location_delete", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, Location $location)
    {
        $dm = $this->getDoctrine()->getManager();
        $dm->remove($location);
        $dm->flush();
        
        $this->get('session')->getFlashBag()->add('success', 'location.msg.removed');
        return new Response(json_encode(array('code' => FormHelper::REFRESH)));
    }
}
