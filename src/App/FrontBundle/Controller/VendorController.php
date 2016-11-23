<?php

namespace App\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Helper\FormHelper;
use App\FrontBundle\Entity\Vendor;
use App\FrontBundle\Form\UserType;
use App\FrontBundle\Entity\Address;

class VendorController extends Controller
{
    /**
     * @Route("/results", name="user_results")
     */
    public function indexResultsAction()
    {
        $datatable = $this->get('app.front.datatable.vendor');
        $datatable->buildDatatable();
        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);
        return $query->getResponse();
    }
    
    /**
     * Displays a form to add an existing vendor entity.
     *
     * @Route("/new", name="vendor_new", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $dm = $this->getDoctrine()->getManager();
        $vendor = new Vendor();
        $vendor->addAddress(new Address());
        $form = $this->createForm(new UserType($dm), $vendor);
        
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $vendor = $form->getData();
                $username = $password = 'VEND'.time();
                $vendor->setUsername($username);
                $vendor->setPassword($password);
                $vendor->setLocale('en');                
                $dm->persist($vendor);
                $dm->flush();
                $this->get('session')->getFlashBag()->add('success', 'vendor.msg.created');
                $code = FormHelper::REFRESH;
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
       
        $body = $this->renderView('AppFrontBundle:Vendor:form.html.twig',
            array('form' => $form->createView())
        );
        
        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    /**
     * Displays a form to edit an existing vendor entity.
     *
     * @Route("/{id}/edit", name="vendor_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Vendor $vendor)
    {
        $dm = $this->getDoctrine()->getManager();
        if($vendor->getAddresses()->count() == 0 && $request->isMethod('GET')){
            $vendor->addAddress(new Address());
        }
        
        $form = $this->createForm(new UserType($dm), $vendor);
        
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $vendor = $form->getData();
                $dm->persist($vendor);
                $dm->flush();
                $this->get('session')->getFlashBag()->add('success', 'vendor.msg.updated');
                $code = FormHelper::REFRESH;
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
        $regions = $vendor->getRegions()->getValues();
        $region_values = array();
        foreach($regions as $region){
            $region_values[] = array('id' => $region->getId(), 'name' => $region->getRegionName().' ('.$region->getDistrict()->getName().')');
        }
        
        $body = $this->renderView('AppFrontBundle:Vendor:form.html.twig',
            array('form' => $form->createView(), 'region_values' => json_encode($region_values))
        );
        
        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    /**
     * Displays a form to delete an existing vendor entity.
     *
     * @Route("/{id}/delete", name="vendor_delete", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, Vendor $vendor)
    {
        $dm = $this->getDoctrine()->getManager();
        $dm->remove($vendor);
        $dm->flush();
        
        $this->get('session')->getFlashBag()->add('success', 'vendor.msg.removed');
        return new Response(json_encode(array('code' => FormHelper::REFRESH)));
    }
    
    /**
     * Displays a form to reset credentials of an existing vendor entity.
     *
     * @Route("/{id}/reset", name="vendor_reset", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function resetAction(Request $request, Vendor $vendor)
    {
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
            ->getForm();
        
        $code = FormHelper::FORM;
        $message = '';
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $obj = $form->getData();
                $encoder = $this->get('security.encoder_factory')->getEncoder($vendor);
                $password = $encoder->encodePassword($obj->password, $vendor->getSalt());
                $vendor->setPassword($password);
                $dm->persist($vendor);
                $dm->flush();
                $this->get('session')->getFlashBag()->add('success', 'vendor.msg.reseted');
                $code = FormHelper::REFRESH;
            } else {
                $message = 'Password doesn\'t match';
                $code = FormHelper::REFRESH_FORM;
            }
        }

        $body = $this->renderView('AppFrontBundle:Vendor:reset.html.twig',
            array('form' => $form->createView(), 'message' => $message)
        );
        
        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    public function loginAction(Request $request)
    {      
        if($this->getUser()){            
            return $this->redirect($this->generateUrl('app_front_vendor_home'));
        }
        
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
		
        return $this->render('AppFrontBundle:Vendor:login.html.twig', array(
            // last username entered by the user
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }
    
    public function logincheckAction(){

    }
    
    public function indexAction(Request $request){
        $stockDatatable = $this->get('app.front.datatable.stock');
        $stockDatatable->buildDatatable(array('vendor' => $this->getUser()));
        return $this->render('AppFrontBundle:Vendor:dashboard.html.twig', array(
            'stockDatatable' => $stockDatatable,
        ));
    }
}
