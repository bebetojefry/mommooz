<?php

namespace App\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Entity\Brand;

class BrandController extends Controller
{    
    /**
     * @Route("/{id}/page", name="brand_page", options={"expose"=true})
     */
    public function pageAction(Brand $brand)
    {       
        return $this->render('AppWebBundle:Brand:index.html.twig', array(
            'brand' => $brand
        ));
    }
}
