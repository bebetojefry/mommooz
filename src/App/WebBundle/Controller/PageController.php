<?php

namespace App\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageController extends Controller
{
    /**
     * @Route("/terms_and_conditions", name="terms_page")
     */
    public function termsAction(Request $request){
        return $this->render('AppWebBundle:Page:terms.html.twig');
    }
    
    /**
     * @Route("/privacy_policy", name="policy_page")
     */
    public function policyAction(Request $request){
        return $this->render('AppWebBundle:Page:policy.html.twig');
    }
    
    /**
     * @Route("/about", name="about_page")
     */
    public function aboutAction(Request $request){
        return $this->render('AppWebBundle:Page:about.html.twig');
    }
    
    /**
     * @Route("/contact", name="contact_page")
     */
    public function contactAction(Request $request){
        return $this->render('AppWebBundle:Page:contact.html.twig');
    }
    
    /**
     * @Route("/faq", name="faq_page")
     */
    public function faqAction(Request $request){
        return $this->render('AppWebBundle:Page:faq.html.twig');
    }
}
