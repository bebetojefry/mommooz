<?php

namespace App\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Entity\VendorItem;
use App\FrontBundle\Helper\FormHelper;
use App\FrontBundle\Entity\Vendor;
class VendorItemController extends Controller
{
    /**
     * @Route("/{id}/results", name="vendoritem_results", options={"expose"=true})
     */
    public function indexResultsAction(Vendor $vendor)
    {
        $datatable = $this->get('app.front.datatable.vendorItem');
        $datatable->buildDatatable(array('vendor' => $vendor));
        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);
        $qb = $query->getQuery();
        if($vendor){
            $qb->andWhere("vendor_item.vendor = :v");
            $qb->setParameter('v', $vendor);
        }
        $query->setQuery($qb);
        
        return $query->getResponse();
    }
    
    /**
     * Delete vendorItem entity.
     *
     * @Route("/{id}/delete", name="vendoritem_delete", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function deleteAction(VendorItem $vendorItem)
    {
        $dm = $this->getDoctrine()->getManager();
        $dm->remove($vendorItem);
        $dm->flush();
        
        $this->get('session')->getFlashBag()->add('success', 'vendoritem.msg.removed');
        return new Response(json_encode(array('code' => FormHelper::REFRESH)));
    }
}
