<?php

namespace App\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Entity\Vendor;

class StoreController extends Controller
{
    /**
     * @Route("/", name="stores")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $stores = $em->getRepository('AppFrontBundle:Vendor')->findBy(array('status' => true));
        
        return $this->render('AppWebBundle:Store:index.html.twig', array(
            'stores' => $stores
        ));
    }
    
    /**
     * @Route("/{id}/items", name="store_items", options={"expose"=true}, defaults={"id" = 0})
     */
    public function itemsAction(Vendor $store = null)
    {
        return $this->render('AppWebBundle:Store:items.html.twig', array(
            'store' => $store
        ));
    }
}
