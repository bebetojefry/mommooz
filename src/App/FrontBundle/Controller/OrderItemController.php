<?php

namespace App\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Helper\FormHelper;
use App\FrontBundle\Entity\Purchase;

class OrderItemController extends Controller
{
    /**
     * @Route("/{id}/results", name="purchaseitem_results")
     */
    public function indexResultsAction(Purchase $purchase)
    {
        $datatable = $this->get('app.front.datatable.purchaseitem');
        $datatable->buildDatatable(array('purchase' => $purchase));
        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);
        $query->buildQuery();
        $qb = $query->getQuery();
        if($purchase){
            $qb->andWhere("purchase_item.purchase = :p");
            $qb->setParameter('p', $purchase);
        }
        $query->setQuery($qb);
        return $query->getResponse(false);
    }
}
