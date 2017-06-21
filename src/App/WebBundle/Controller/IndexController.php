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

    public function switchLocationAction(){
        $this->get('session')->set('region', $_POST['region']);
        return $this->redirect($this->generateUrl('home'));
    }
    
    public function exploreskipAction(){
        $em = $this->getDoctrine()->getManager();
        $region = $em->getRepository('AppFrontBundle:Region')->findOneByDefault(true);
        if($region){
            $this->get('session')->set('district', $region->getDistrict()->getId());
            $this->get('session')->set('region', $region->getId());
        } else {
            $this->get('session')->set('district', 0);
            $this->get('session')->set('region', 0);
        }

        return $this->redirect($this->generateUrl('home'));
    }
}
