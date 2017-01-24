<?php

namespace App\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Entity\Offer;

class offerController extends Controller
{
    /**
     * @Route("/", name="offers")
     */
    public function indexAction()
    {
        $now = new \DateTime('now');
        $now->setTime(0, 0, 0);
        $offers = $this->getDoctrine()->getManager()->getRepository("AppFrontBundle:Offer")->createQueryBuilder('o')
            ->where('o.status = :s and o.expiry >= :now')
            ->setParameter('s', true)
            ->setParameter('now', $now)
            ->getQuery()
            ->getResult();
         
        return $this->render('AppWebBundle:Offer:index.html.twig', array(
            'offers' => $offers
        ));
    }
    
    /**
     * @Route("/{id}/items", name="offer_items", options={"expose"=true}, defaults={"id" = 0})
     */
    public function itemsAction(Offer $offer = null)
    {
        return $this->render('AppWebBundle:Offer:items.html.twig', array(
            'offer' => $offer
        ));
    }
}
