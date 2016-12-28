<?php

namespace App\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Helper\FormHelper;
use App\FrontBundle\Entity\Purchase;

class OrderController extends Controller
{
    /**
     * @Route("/results", name="purchase_results")
     */
    public function indexResultsAction()
    {
        $datatable = $this->get('app.front.datatable.purchase');
        $datatable->buildDatatable();
        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);
        return $query->getResponse();
    }
    
    /**
     * Displays a all items in the purchase entity.
     *
     * @Route("/{id}/items", name="purchase_show", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function itemsAction(Request $request, Purchase $purchase)
    {
        $itemsDatatable = $this->get('app.front.datatable.purchaseitem');
        $itemsDatatable->buildDatatable(array('purchase' => $purchase));
        return $this->render('AppFrontBundle:Order:items.html.twig', array(
            'itemsDatatable' => $itemsDatatable,
            'purchase' => $purchase
        ));
    }
    
    /**
     * Change status of a purchase entity.
     *
     * @Route("/{id}/status", name="change_status", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function statusAction(Request $request, Purchase $purchase)
    {
        echo 'status'; exit;
    }
}
