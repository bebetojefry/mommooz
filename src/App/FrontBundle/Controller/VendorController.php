<?php

namespace App\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\FrontBundle\Helper\FormHelper;
use App\FrontBundle\Entity\Vendor;
use App\FrontBundle\Form\UserType;
use App\FrontBundle\Entity\Address;
use App\FrontBundle\Entity\Item;
use App\FrontBundle\Entity\VendorItem;
use App\FrontBundle\Entity\Stock;

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
        $form = $this->createForm(new UserType($dm, $this->get('router')), $vendor);
        
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $vendor = $form->getData();
                $username = $vendor->getEmail();
                $password = 'VEND'.time();
                $vendor->setUsername($username);
                $vendor->setPassword($password);
                $vendor->setLocale('en');      
                $dm->persist($vendor);
                $dm->flush();
                
                $stock = new Stock();
                $stock->setName('My Stock');
                $stock->setVendor($vendor);
                $stock->setDate(new \DateTime('now'));
                $stock->setStatus(TRUE);
                $dm->persist($stock);
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
        
        $form = $this->createForm(new UserType($dm, $this->get('router')), $vendor);
        
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $vendor = $form->getData();
                $vendor->setUsername($vendor->getEmail());
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
    
    public function passwordAction(Request $request){
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
                $this->get('session')->getFlashBag()->add('success', 'admin.msg.reseted');
            } else {
                $this->get('session')->getFlashBag()->add('warning', 'Password doesn\'t match');
            }
        }

        return $this->render('AppFrontBundle:Vendor:password.html.twig',
            array('form' => $form->createView())
        );
    }
    
    public function profileAction(Request $request){
        $dm = $this->getDoctrine()->getManager();
        $vendor = $this->getUser();
        
        $form = $this->createForm(new UserType($dm), $vendor);
        
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $vendor = $form->getData();
                $dm->persist($vendor);
                $dm->flush();
                $this->get('session')->getFlashBag()->add('success', 'vendor.msg.profile.updated');
            } else {
                $this->get('session')->getFlashBag()->add('warning', 'vendor.msg.profile.error');
            }
        }
        
        return $this->render('AppFrontBundle:Vendor:profile.html.twig',
            array('form' => $form->createView())
        );
    }
    
    public function itemsAction(){
        $vendorItemDatatable = $this->get('app.front.datatable.vendorItem');
        $vendorItemDatatable->buildDatatable(array('vendor' => $this->getUser()));
        return $this->render('AppFrontBundle:Vendor:items.html.twig', array(
            'vendorItemDatatable' => $vendorItemDatatable
        ));
    }
    
    public function itemautocompleteAction(Request $request) {
        $q = $request->query->get('q');
        $repo = $this->getDoctrine()->getManager()->getRepository('AppFrontBundle:Item');
        $items = $repo->createQueryBuilder('i')
                ->where('i.name LIKE :q')
                ->setParameter('q', '%'.$q.'%')
                ->getQuery()
                ->getResult();
        
        $result = array();
        foreach($items as $item){
            $result[] = array('id' => $item->getId(), 'title' => $item->getBrand()->getName().' '.$item->getName());
        }
        
        return new JsonResponse($result); 
    }
    
    public function itempreviewAction(Request $request, Item $item) {
        $em = $this->getDoctrine()->getManager();
        if($request->getMethod() == Request::METHOD_POST){
            
            $vendorItem = new VendorItem();
            $vendorItem->setVendor($this->getUser());
            $vendorItem->setItem($item);
            $vendorItem->setStatus(true);
            $em->persist($vendorItem);
            $em->flush();
            
            
            $this->get('session')->getFlashBag()->add('success', 'vendor.msg.item.added');
            
            return $this->redirect($this->generateUrl('app_front_vendor_items'));
        }
        
        $vendorItem = $em->getRepository('AppFrontBundle:VendorItem')->findOneBy(array('vendor' => $this->getUser(), 'item' => $item));
        
        return $this->render('AppFrontBundle:Vendor:items.html.twig', array(
            'item' => $item,
            'vendorItem' => $vendorItem
        ));
    }
    
    public function ordersAction(){
        $ordersDatatable = $this->get('app.front.datatable.purchaseitem');
        $ordersDatatable->buildDatatable(array('vendor' => $this->getUser()));
        return $this->render('AppFrontBundle:Vendor:orders.html.twig', array(
            'ordersDatatable' => $ordersDatatable
        ));
    }
    
    public function ordersResultAction(Request $request, Vendor $vendor){
        $datatable = $this->get('app.front.datatable.purchaseitem');
        $datatable->buildDatatable(array('vendor' => $vendor));
        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);
        $query->buildQuery();
        $qb = $query->getQuery();
        if($vendor){
            $qb->innerJoin('purchase_item.entry','e');
            $qb->innerJoin('e.stock','s');
            $qb->andWhere("s.vendor = :v");
            $qb->setParameter('v', $vendor);
        }
        $query->setQuery($qb);
        return $query->getResponse(false);
    }
    
    /**
     * @Route("/email/validate/{id}", name="email_validate", defaults={"id":0}, options={"expose"=true})
     */
    public function emailValidateAction(Vendor $vendor = null)
    {
        $email = $_GET['app_frontbundle_user']['email'];
        if($vendor){
            $vendors = $this->getDoctrine()->getManager()->getRepository("AppFrontBundle:Vendor")->createQueryBuilder('v')
            ->where('v.email = :email and v.id != :id')
            ->setParameter('email', $email)
            ->setParameter('id', $vendor->getId())
            ->getQuery()
            ->getResult();
        } else {
            $vendors = $this->getDoctrine()->getManager()->getRepository("AppFrontBundle:Vendor")->createQueryBuilder('v')
            ->where('v.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getResult();
        }
        
        if(count($vendors) == 0){
            return new Response('OK', 200);
        }
        
        return new Response('Invalid', 406);
    }
    
    public function invoicesAction(){
        $invoicesDatatable = $this->get('app.front.datatable.invoice');
        $invoicesDatatable->buildDatatable(array('vendor' => $this->getUser()));
        return $this->render('AppFrontBundle:Vendor:invoice.html.twig', array(
            'invoicesDatatable' => $invoicesDatatable
        ));
    }
    
    /**
     * @Route("/{id}/entries", name="vendor_entries", defaults={"id":0}, options={"expose"=true})
     */
    public function entriesAction(Vendor $vendor = null)
    {
        $stock = null;
        if($vendor){
            $stock = $vendor->getStocks()->first();
        }
        $itemsDatatable = $this->get('app.front.datatable.adminstockentry');
        $itemsDatatable->buildDatatable(array('stock' => $stock));
        return $this->render('AppFrontBundle:Stock:adminItems.html.twig', array(
            'itemsDatatable' => $itemsDatatable,
            'stock' => $stock
        ));
    }
}
