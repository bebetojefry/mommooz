<?php

namespace App\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        if($this->get('session')->get('region') !== null && $this->get('session')->get('location') !== null){
            return $this->render('AppWebBundle:Index:index.html.twig');
        }

        $em = $this->getDoctrine()->getManager();
        $regions = $em->getRepository('AppFrontBundle:Region')->findAll();
        $locations = array();
        if(count($regions) > 0){
            $locations = $regions[0]->getLocations();
        }
        return $this->render('AppWebBundle:Index:explore.html.twig', array(
            'regions' => $regions,
            'locations' => $locations
        ));
    }

    public function exploreAction(){
        $em = $this->getDoctrine()->getManager();
        if(isset($_POST['btnSubmit'])){
            $this->get('session')->set('region', $_POST['region']);
            $this->get('session')->set('location', $_POST['location']);

            return $this->redirect($this->generateUrl('home'));
        } else {
            $regions = $em->getRepository('AppFrontBundle:Region')->findAll();
            $region_selected = $em->getRepository('AppFrontBundle:Region')->find($_POST['region']);
            $locations = $em->getRepository('AppFrontBundle:Location')->findByRegion($region_selected);

            return $this->render('AppWebBundle:Index:explore.html.twig', array(
                'regions' => $regions,
                'locations' => $locations
            ));
        }
    }

    public function switchLocationAction(){
        $this->get('session')->set('location', $_POST['location']);
        return $this->redirect($this->generateUrl('home'));
    }
    
    public function exploreskipAction(){
        $this->get('session')->set('region', 0);
        $this->get('session')->set('location', 0);

        return $this->redirect($this->generateUrl('home'));
    }
}
