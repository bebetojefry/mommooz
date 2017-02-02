<?php

namespace App\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Helper\FormHelper;
use App\FrontBundle\Entity\Invoice;
use App\FrontBundle\Form\InvoiceType;

class InvoiceController extends Controller
{
    /**
     * @Route("/results", name="invoice_results")
     */
    public function indexResultsAction()
    {
        $datatable = $this->get('app.front.datatable.invoice');
        $datatable->buildDatatable();
        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);
        return $query->getResponse();
    }
    
    /**
     * @Route("/{id}/results", name="invoice_item_results", options={"expose"=true})
     */
    public function itemResultsAction(Request $request, Invoice $invoice)
    {
        $datatable = $this->get('app.front.datatable.invoiceitem');
        $datatable->buildDatatable(array('invoice' => $invoice));
        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);
        $Ids = array_map(create_function('$o', 'return $o->getId();'), $invoice->getItems()->getValues());        
        $query->buildQuery();
        $qb = $query->getQuery();
        if($invoice){
            $qb->andWhere("purchase_item.id in (:ids)");
            $qb->setParameter('ids', $Ids);
        }
        $query->setQuery($qb);
        return $query->getResponse(false);
    }
    
    /**
     * Displays a all items in the invoice entity.
     *
     * @Route("/{id}/detail", name="invoice_show", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function itemsAction(Request $request, Invoice $invoice)
    {
        $itemsDatatable = $this->get('app.front.datatable.invoiceitem');
        $itemsDatatable->buildDatatable(array('invoice' => $invoice));
        return $this->render('AppFrontBundle:Invoice:items.html.twig', array(
            'itemsDatatable' => $itemsDatatable,
            'invoice' => $invoice
        ));
    }
    
    /**
     * Create new ivoice entity.
     *
     * @Route("/new", name="invoice_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $dm = $this->getDoctrine()->getManager();
        $start = new \DateTime('now');
        $start->setTime(0, 0, 0);
        $start->modify('-1 month');
        $end = new \DateTime('now');
        $end->setTime(23, 59, 59);
        $invoice = new Invoice();
        $invoice->setDateFrom($start);
        $invoice->setDateTo($end);
        $form = $this->createForm(new InvoiceType(), $invoice);
                
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $invoice = $form->getData();
                $invoice->setStatus(false);
                $invoice->setCreatedOn(new \DateTime('now'));
                
                $qb = $dm->createQueryBuilder();
                $qb->select('pi')
                    ->from('AppFrontBundle:PurchaseItem', 'pi')
                    ->join('pi.purchase', 'p')
                    ->join('pi.entry','e')
                    ->join('e.stock','s')
                    ->where('p.purchasedOn >= :start and p.purchasedOn <= :end and p.status = :status and s.vendor = :v')
                    ->orderBy('p.purchasedOn', 'ASC')
                    ->setParameter('v', $invoice->getVendor())
                    ->setParameter('status', 4)
                    ->setParameter('start', $invoice->getDateFrom())
                    ->setParameter('end', $invoice->getDateTo());
                
                $items = $qb->getQuery()->getResult();
                if(count($items) > 0){
                    foreach($items as $item){
                        $invoice->addItem($item);
                    }
                    $dm->persist($invoice);
                    $dm->flush();
                    $this->get('session')->getFlashBag()->add('success', 'invoice.msg.created');
                } else {
                    $this->get('session')->getFlashBag()->add('warning', 'invoice.msg.no_purchase_found');
                }
               
                $code = FormHelper::REFRESH;
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
        $body = $this->renderView('AppFrontBundle:Invoice:form.html.twig',
            array('form' => $form->createView())
        );
        
        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
}
