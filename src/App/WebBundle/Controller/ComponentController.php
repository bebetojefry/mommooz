<?php

namespace App\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Entity\Category;

class ComponentController extends Controller
{    
    /**
     * @Route("/banner", name="component_banner")
     */
    public function bannerAction()
    {
        $em = $this->getDoctrine()->getManager();
        $banners = $em->getRepository('AppFrontBundle:Banner')->findAll();
        return $this->render('AppWebBundle:Component:banner.html.twig', array('banners' => $banners));
    }
}
