<?php

namespace App\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Entity\Product;

class AdminController extends Controller
{
    public function dashboardAction(Request $request){
        $regionDatatable = $this->get('app.front.datatable.region');
        $regionDatatable->buildDatatable();
        return $this->render('AppFrontBundle:Admin:dashboard.html.twig', array(
            'regionDatatable' => $regionDatatable,
        ));
    }
    
    public function regionAction(Request $request){
        $regionDatatable = $this->get('app.front.datatable.region');
        $regionDatatable->buildDatatable();
        return $this->render('AppFrontBundle:Admin:region.html.twig', array(
            'regionDatatable' => $regionDatatable,
        ));
    }
    
    public function categoryAction(Request $request){
        $categoryDatatable = $this->get('app.front.datatable.category');
        $categoryDatatable->buildDatatable();
        return $this->render('AppFrontBundle:Admin:category.html.twig', array(
            'categoryDatatable' => $categoryDatatable,
        ));
    }
    
    public function productAction(Request $request){
        $productDatatable = $this->get('app.front.datatable.product');
        $productDatatable->buildDatatable();
        return $this->render('AppFrontBundle:Admin:product.html.twig', array(
            'productDatatable' => $productDatatable,
        ));
    }
    
    public function brandAction(Request $request){
        $brandDatatable = $this->get('app.front.datatable.brand');
        $brandDatatable->buildDatatable();
        return $this->render('AppFrontBundle:Admin:brand.html.twig', array(
            'brandDatatable' => $brandDatatable,
        ));
    }
    
    public function variantAction(Request $request){
        $variantDatatable = $this->get('app.front.datatable.variant');
        $variantDatatable->buildDatatable();
        return $this->render('AppFrontBundle:Admin:variant.html.twig', array(
            'variantDatatable' => $variantDatatable,
        ));
    }
    
    public function bannerAction(Request $request){
        $bannerDatatable = $this->get('app.front.datatable.banner');
        $bannerDatatable->buildDatatable();
        return $this->render('AppFrontBundle:Admin:banner.html.twig', array(
            'bannerDatatable' => $bannerDatatable,
        ));
    }
    
    public function vendorAction(Request $request){
        $vendorDatatable = $this->get('app.front.datatable.vendor');
        $vendorDatatable->buildDatatable();
        return $this->render('AppFrontBundle:Admin:vendor.html.twig', array(
            'vendorDatatable' => $vendorDatatable,
        ));
    }
    
    public function consumerAction(Request $request){
        $consumerDatatable = $this->get('app.front.datatable.consumer');
        $consumerDatatable->buildDatatable();
        return $this->render('AppFrontBundle:Admin:consumer.html.twig', array(
            'consumerDatatable' => $consumerDatatable,
        ));
    }
    
    public function orderAction(Request $request){
        $purchaseDatatable = $this->get('app.front.datatable.purchase');
        $purchaseDatatable->buildDatatable();
        return $this->render('AppFrontBundle:Admin:purchase.html.twig', array(
            'purchaseDatatable' => $purchaseDatatable,
        ));
    }
    
    public function profileAction(Request $request){
        $user = $this->getUser();
        
        $dm = $this->getDoctrine()->getManager();
        $obj = new \stdClass();
        $obj->password = '';
        $form = $this->createFormBuilder($obj)
            ->add('password', 'repeated', array(
                'type' => 'password',
                'required' => true,
                'invalid_message' => 'The password fields must match.',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
            ->add('submit', 'submit', array('attr' => array('class' => 'btn btn-primary')))
            ->getForm();
        
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $obj = $form->getData();
                $encoder = $this->get('security.encoder_factory')->getEncoder($user);
                $password = $encoder->encodePassword($obj->password, $user->getSalt());
                $user->setPassword($password);
                $dm->persist($user);
                $dm->flush();
                $this->get('session')->getFlashBag()->add('success', 'Password changed successfully');
            } else {
                $this->get('session')->getFlashBag()->add('warning', 'Password doesn\'t match');
            }
        }

        return $this->render('AppFrontBundle:Admin:reset.html.twig',
            array('form' => $form->createView())
        );
    }
    
    public function offerAction(Request $request){
        $offerDatatable = $this->get('app.front.datatable.offer');
        $offerDatatable->buildDatatable();
        return $this->render('AppFrontBundle:Admin:offer.html.twig', array(
            'offerDatatable' => $offerDatatable,
        ));
    }
    
    public function configAction(Request $request){
        $configDatatable = $this->get('app.front.datatable.config');
        $configDatatable->buildDatatable();
        return $this->render('AppFrontBundle:Admin:config.html.twig', array(
            'configDatatable' => $configDatatable,
        ));
    }
    
    public function invoiceAction(Request $request){
        $invoiceDatatable = $this->get('app.front.datatable.invoice');
        $invoiceDatatable->buildDatatable();
        return $this->render('AppFrontBundle:Admin:invoice.html.twig', array(
            'invoiceDatatable' => $invoiceDatatable,
        ));
    }
}
