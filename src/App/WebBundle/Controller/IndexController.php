<?php

namespace App\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{
    public function indexAction()
    {
        return $this->render('AppWebBundle:Index:index.html.twig');
    }

    public function exploreAction(){
        $em = $this->getDoctrine()->getManager();
        if(isset($_POST['btnSubmit'])){
            $this->get('session')->set('district', $_POST['explore_district']);
            $this->get('session')->set('region', $_POST['explore_region']);
        }
        
        return $this->redirect($this->generateUrl('home'));
    }
    
    public function regionsAction(){
        $em = $this->getDoctrine()->getManager();
        $distid = isset($_POST['district']) ? $_POST['district'] : 35;
        $district_selected = $em->getRepository('AppFrontBundle:District')->find($distid);
        $regions = $em->getRepository('AppFrontBundle:Region')->findByDistrict($district_selected);
        
        $result= array();
        foreach($regions as $region){
           $result[] = '<option value="'.$region->getId().'">'.$region->getRegionName().'</option>'; 
        }
        
        return new Response(implode($result, ''));
    }
    
    public function setRegionAction(){
        $em = $this->getDoctrine()->getManager();
        if(isset($_POST['region'])){
            $region = $em->getRepository('AppFrontBundle:Region')->find($_POST['region']);
            $this->get('session')->set('district', $region->getDistrict()->getId());
            $this->get('session')->set('region', $region->getId());
        }
        
        return new Response('1');
    }

    public function switchLocationAction(){
        $this->get('session')->set('region', $_POST['region']);
        return $this->redirect($this->generateUrl('home'));
    }
    
    public function exploreskipAction(){
        $this->get('app.web.user')->setDefaultRegion();
        return $this->redirect($this->generateUrl('home'));
    }
}
