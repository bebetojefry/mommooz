<?php

namespace App\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Helper\FormHelper;
use App\FrontBundle\Entity\Consumer;
use App\FrontBundle\Form\ConsumerType;
use App\FrontBundle\Entity\Address;
use App\FrontBundle\Entity\Cart;

class ConsumerController extends Controller
{
    /**
     * @Route("/results", name="consumer_results")
     */
    public function indexResultsAction()
    {
        $datatable = $this->get('app.front.datatable.consumer');
        $datatable->buildDatatable();
        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);
        return $query->getResponse();
    }
    
    /**
     * Displays a form to add an existing consumer entity.
     *
     * @Route("/new", name="consumer_new", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $dm = $this->getDoctrine()->getManager();
        $consumer = new Consumer();
        $consumer->addAddress(new Address());
        $form = $this->createForm(new ConsumerType($dm, $this->get('router')), $consumer);
        
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $consumer = $form->getData();
                $username = $consumer->getEmail();
                $password = 'CONS'.time();
                $consumer->setUsername($username);
                $consumer->setPassword($password);
                $consumer->setLocale('en');      
                $dm->persist($consumer);
                $dm->flush();
                
                $this->get('session')->getFlashBag()->add('success', 'consumer.msg.created');
                $code = FormHelper::REFRESH;
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
       
        $body = $this->renderView('AppFrontBundle:Consumer:form.html.twig',
            array('form' => $form->createView())
        );
        
        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    /**
     * Displays a form to edit an existing consumer entity.
     *
     * @Route("/{id}/edit", name="consumer_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Consumer $consumer)
    {
        $dm = $this->getDoctrine()->getManager();
        if($consumer->getAddresses()->count() == 0 && $request->isMethod('GET')){
            $consumer->addAddress(new Address());
        }
        
        $form = $this->createForm(new ConsumerType($dm, $this->get('router')), $consumer);
        
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $consumer = $form->getData();
                $consumer->setUsername($consumer->getEmail());
                $dm->persist($consumer);
                $dm->flush();
                $this->get('session')->getFlashBag()->add('success', 'consumer.msg.updated');
                $code = FormHelper::REFRESH;
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }

        $body = $this->renderView('AppFrontBundle:Consumer:form.html.twig',
            array('form' => $form->createView())
        );
        
        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    /**
     * Displays a form to delete an existing consumer entity.
     *
     * @Route("/{id}/delete", name="consumer_delete", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, Consumer $consumer)
    {
        $dm = $this->getDoctrine()->getManager();
        $dm->remove($consumer);
        $dm->flush();
        
        $this->get('session')->getFlashBag()->add('success', 'consumer.msg.removed');
        return new Response(json_encode(array('code' => FormHelper::REFRESH)));
    }
    
    
    /**
     * Displays consumer cart.
     *
     * @Route("/{id}/cart", name="consumer_cart", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function cartAction(Request $request, Consumer $consumer)
    {
        $em = $this->getDoctrine()->getManager();
        if(!$cart = $consumer->getCart()){
            $cart = new Cart();
            $cart->setUser($consumer);
            $cart->setSessionId(session_id());
            $em->persist($cart);
            $em->flush();
        }
        
        $cartItemDatatable = $this->get('app.front.datatable.cartitem');
        $cartItemDatatable->buildDatatable(array('cart' => $cart));
        return $this->render('AppFrontBundle:Consumer:cart.html.twig', array(
            'cartItemDatatable' => $cartItemDatatable,
            'consumer' => $consumer
        ));
    }
    
    /**
     * Displays consumer orders.
     *
     * @Route("/{id}/orders", name="consumer_orders", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function orderAction(Request $request, Consumer $consumer){
        $purchaseDatatable = $this->get('app.front.datatable.purchase');
        $purchaseDatatable->buildDatatable(array('consumer' => $consumer));
        return $this->render('AppFrontBundle:Consumer:purchase.html.twig', array(
            'purchaseDatatable' => $purchaseDatatable,
            'consumer' => $consumer
        ));
    }
}
