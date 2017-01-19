<?php

namespace App\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        if($this->get('session')->get('district') !== null && $this->get('session')->get('region') !== null){
            return $this->render('AppWebBundle:Index:index.html.twig');
        }

        $em = $this->getDoctrine()->getManager();
        $districts = $em->getRepository('AppFrontBundle:District')->findAll();
        $regions = array();
        if(count($districts) > 0){
            $regions = $districts[0]->getRegions();
        }
        
        return $this->render('AppWebBundle:Index:explore.html.twig', array(
            'districts' => $districts,
            'regions' => $regions
        ));
    }

    public function exploreAction(){
        $em = $this->getDoctrine()->getManager();
        if(isset($_POST['btnSubmit'])){
            $this->get('session')->set('district', $_POST['district']);
            $this->get('session')->set('region', $_POST['region']);

            return $this->redirect($this->generateUrl('home'));
        } else {
            $districts = $em->getRepository('AppFrontBundle:District')->findAll();
            $district_selected = $em->getRepository('AppFrontBundle:District')->find($_POST['district']);
            $regions = $em->getRepository('AppFrontBundle:Region')->findByDistrict($district_selected);

            return $this->render('AppWebBundle:Index:explore.html.twig', array(
                'districts' => $districts,
                'regions' => $regions
            ));
        }
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
