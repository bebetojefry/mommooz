<?php

namespace App\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Helper\FormHelper;
use App\FrontBundle\Entity\Purchase;
use App\FrontBundle\Form\PurchaseType;
use App\FrontBundle\Entity\Consumer;

class OrderController extends Controller
{
    /**
     * @Route("/{id}/results", name="purchase_results", options={"expose"=true})
     */
    public function indexResultsAction(Request $request, Consumer $consumer = null)
    {
        $datatable = $this->get('app.front.datatable.purchase');
        $datatable->buildDatatable(array('consumer' => $consumer));
        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);
        $query->buildQuery();
        $qb = $query->getQuery();
        if($consumer){
            $qb->andWhere("purchase.consumer = :c");
            $qb->setParameter('c', $consumer);
        }
        
        $query->setQuery($qb);
        return $query->getResponse(false);
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
            'purchase' => $purchase,
            'status' => array(0 => 'Pending', 1 => 'Confirmed', 2 => 'Processing', 3=> "Out for delivered", 4 => 'Delivered', 5 => 'Cancelled')
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
        $dm = $this->getDoctrine()->getManager();
        $form = $this->createForm(new PurchaseType($request), $purchase);
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            if($form->isValid() && $_POST['form_state'] == ''){
                $form->handleRequest($request);
                $purchase = $form->getData();
                $dm->persist($purchase);
                $dm->flush();
                
                //send order email to customer
                $statusArr = array(0 => 'Pending', 1 => 'Confirmed', 2 => 'Processing', 3=> "Out for delivered", 4 => 'Delivered', 5 => 'Cancelled');
                $content = $this->renderView('AppWebBundle:Account:orderUpdateTemplate.html.twig',
                    array(
                        'order' => $purchase,
                        'status' => $statusArr[$purchase->getStatus()]
                    )
                );

                $message = \Swift_Message::newInstance()
                ->setSubject('Mommooz Order Update')
                ->setFrom($this->getParameter('email_from'))
                ->addTo($purchase->getConsumer()->getEmail(), $purchase->getConsumer()->getFullName())
                ->setBody($content, 'text/html');

                try {
                    $this->get('mailer')->send($message);
                } catch(\Exception $e) {

                }
                
                // delete all stock purchases related to this purchase if its cancelled
                if($purchase->getStatus() == 5){
                    $qb = $dm->createQueryBuilder();
                    $qb->delete('AppFrontBundle:StockPurchase', 'sp');
                    $qb->where('sp.purchase = :purchase');
                    $qb->setParameter('purchase', $purchase);
                    $qb->getQuery()->execute();
                }
                
                $this->get('session')->getFlashBag()->add('success', 'purchase.msg.updated');
                $code = FormHelper::REFRESH;
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
        $body = $this->renderView('AppFrontBundle:Order:form.html.twig',
            array('form' => $form->createView())
        );

        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    /**
     * Clear a all stock items in the purchase entity.
     *
     * @Route("/{id}/items/clear", name="purchase_clear", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function clearAction(Request $request, Purchase $purchase)
    {
        $dm = $this->getDoctrine()->getManager();
        $stockPurchases = $dm->getRepository("AppFrontBundle:StockPurchase")->createQueryBuilder('sp')
        ->where('sp.purchase = :p')
        ->setParameter('p', $purchase)
        ->getQuery()
        ->getResult();
        
        foreach($stockPurchases as $stockPurchase){
            $dm->remove($stockPurchase);
        }
        
        $dm->flush();
        
        $this->get('session')->getFlashBag()->add('success', 'purchase.msg.cleared');
        return new Response(json_encode(array('code' => FormHelper::REFRESH)));
    }
}
