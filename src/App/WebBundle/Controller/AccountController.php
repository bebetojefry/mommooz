<?php

namespace App\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Entity\CartItem;
use App\FrontBundle\Entity\WishListItem;
use App\FrontBundle\Entity\Consumer;
use App\FrontBundle\Form\RegisterType;
use App\FrontBundle\Entity\Address;
use App\FrontBundle\Entity\Purchase;
use App\FrontBundle\Entity\PurchaseItem;
use App\FrontBundle\Form\ProfileType;
use App\FrontBundle\Form\AddressType;
use App\FrontBundle\Entity\StockPurchase;

class AccountController extends Controller
{
    /**
     * @Route("/register", name="register_page")
     */
    public function registerAction(Request $request){
        if($this->getUser()){            
            return $this->redirect($this->generateUrl('home'));
        }
        $em = $this->getDoctrine()->getManager();
        $consumer = $this->get('app.front.entity.consumer');
        $consumer->regenerateSalt();
        $address = new Address();
        $address->setDefault(true);
        $consumer->addAddress($address);
        $form = $this->createForm(new RegisterType(), $consumer);
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $consumer = $form->getData();
                $consumer->setUsername($consumer->getEmail());
                $consumer->setLocale('en');
                $consumer->setCreatedOn(new \DateTime('now'));
                $consumer->setStatus(false);
                $em->persist($consumer);
                $em->flush();
                
                //send verification email to customer
                $content = $this->renderView('AppWebBundle:Account:activateTemplate.html.twig',
                    array('consumer' => $consumer)
                );
                
                $message = \Swift_Message::newInstance()
                ->setSubject('Mommooz Registration Activation')
                ->setFrom($this->getParameter('email_from'))
                ->addTo($consumer->getEmail(), $consumer->getFullName())
                ->setBody($content, 'text/html');

                $this->get('mailer')->send($message);
                
                return $this->redirect($this->generateUrl('register_thank_you'));
            }
        }
        
        return $this->render('AppWebBundle:Account:register.html.twig', array('form' => $form->createView()));
    }
    
    /**
     * @Route("/register/thankyou", name="register_thank_you")
     */
    public function thankyouAction()
    {
        if($this->getUser()){            
            return $this->redirect($this->generateUrl('home'));
        }
        return $this->render('AppWebBundle:Account:thankyou.html.twig');
    }
    
    /**
     * @Route("/activate/{id}", name="activate_consumer")
     */
    public function activateAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $id = $this->get('nzo_url_encryptor')->decrypt($id);
        if(is_numeric($id)){
            $consumer = $em->getRepository('AppFrontBundle:Consumer')->find($id);
            if($consumer){
                if(!$consumer->getStatus()){
                    $consumer->setStatus(true);
                    $em->persist($consumer);
                    $em->flush();
                    $msg = 'Account activated successfully.';
                } else {
                    $msg = 'Account already active.';
                }
            } else {
                $msg = 'Invalid activation link.';
            }
        } else {
            $msg = 'Invalid activation link.';
        }
        
        return $this->render('AppWebBundle:Account:activation.html.twig', array(
            'msg' => $msg
        ));
    }
    
    /**
     * @Route("/login", name="login_page")
     */
    public function loginAction(){
        if($this->getUser()){            
            return $this->redirect($this->generateUrl('home'));
        }
        
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('AppWebBundle:Account:login.html.twig', array(
            // last username entered by the user
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }
    
    /**
     * @Route("/", name="account_home")
     */
    public function accountAction(Request $request)
    {
        $consumer = $this->getUser();
        $em = $this->getDoctrine()->getManager();
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
        
        $message = '';
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $obj = $form->getData();
                $encoder = $this->get('security.encoder_factory')->getEncoder($consumer);
                $password = $encoder->encodePassword($obj->password, $consumer->getSalt());
                $consumer->setPassword($password);
                $em->persist($consumer);
                $em->flush();
                $message = 'Password successfully changed.';
            } else {
                $message = 'Password doesn\'t match.';
            }
        }
        
        return $this->render('AppWebBundle:Account:index.html.twig',
            array('form' => $form->createView(), 'message' => $message)
        );
    }
    
    /**
     * @Route("/cart", name="cart_page")
     */
    public function cartAction()
    {
        return $this->render('AppWebBundle:Account:cart.html.twig');
    }
    
    /**
     * @Route("/cart/{id}/delete", name="cart_delete", options={"expose"=true})
     */
    public function cartdeleteAction(CartItem $item)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($item);
        $em->flush();
        
        return $this->redirect($this->generateUrl('cart_page'));
    }
    
    /**
     * @Route("/wishlist", name="wishlist_page")
     */
    public function wishlistAction()
    {
        return $this->render('AppWebBundle:Account:wishlist.html.twig');
    }
    
    /**
     * @Route("/wishlist/{id}/delete", name="wishlist_delete", options={"expose"=true})
     */
    public function wishlistdeleteAction(WishListItem $item)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($item);
        $em->flush();
        
        return $this->redirect($this->generateUrl('wishlist_page'));
    }
    
    /**
     * @Route("/profile", name="profile_page")
     */
    public function profileAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $consumer = $this->getUser();
        $form = $this->createForm(new ProfileType($em), $consumer);
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $consumer = $form->getData();
                $em->persist($consumer);
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('success', 'Profile updated successfully.');
                
                return $this->redirect($this->generateUrl('profile_page'));
            }
        }
        return $this->render('AppWebBundle:Account:profile.html.twig', array('form' => $form->createView()));
    }
    
    /**
     * @Route("/orders", name="orders_page")
     */
    public function ordersAction()
    {
        $em = $this->getDoctrine()->getManager();
        $orders = $em->getRepository('AppFrontBundle:Purchase')->findByConsumer($this->getUser());
        return $this->render('AppWebBundle:Account:orders.html.twig',
            array(
                'orders' => $orders,
                'status' => array(0 => 'Pending', 1 => 'Confirmed', 2 => 'Processing', 3=> "Out for delivered", 4 => 'Delivered', 5 => 'Cancelled')
            )
        );
    }
    
    /**
     * @Route("/orders/{id}/items", name="order_detail_page", options={"expose"=true})
     */
    public function orderdetailAction(Purchase $order)
    {        
        return $this->render('AppWebBundle:Account:orderItems.html.twig',
            array('order' => $order)
        );
    }
    
    /**
     * @Route("/orders/{id}/cancel", name="order_cancel", options={"expose"=true})
     */
    public function ordercancelAction(Purchase $order)
    {        
        $em = $this->getDoctrine()->getManager();
        $order->setStatus(5);
        $order->setCancelledOn(new \DateTime('now'));
        $em->persist($order);
        $em->flush();
        
        $this->get('session')->getFlashBag()->add('success', 'Order cancelled successfully.');
        
        return $this->redirect($this->generateUrl('orders_page'));
    }
    
    /**
     * @Route("/orders/items/{id}/remove", name="order_item_remove", options={"expose"=true})
     */
    public function orderitemremoveAction(PurchaseItem $orderItem)
    {        
        $orderId = $orderItem->getPurchase()->getId();
        $em = $this->getDoctrine()->getManager();
        $em->remove($orderItem);
        $em->flush();
        
        $this->get('session')->getFlashBag()->add('success', 'Order item removed successfully.');
        
        return $this->redirect($this->generateUrl('order_detail_page', array('id' => $orderId)));
    }
    
    /**
     * @Route("/addresses", name="address_page")
     */
    public function addressAction()
    {
        return $this->render('AppWebBundle:Account:address.html.twig');
    }
    
    /**
     * @Route("/addresses/form/{id}", name="address_form", defaults={"id":0}, options={"expose"=true})
     */
    public function addressformAction(Request $request, Address $address = null)
    {
        $em = $this->getDoctrine()->getManager();
        $mode = $address == null ? 'new' : 'edit';
        $address = $address == null ? new Address() : $address;
        $form = $this->createForm(new AddressType(), $address);
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $address = $form->getData();
                $em->persist($address);
                $em->flush();
                                
                if($mode == 'new'){
                    $this->getUser()->addAddress($address);
                    $em->persist($this->getUser());
                    $em->flush();
                }
                
                $this->get('session')->getFlashBag()->add('success', 'Address updated successfully.');
                
                return $this->redirect($this->generateUrl('address_page'));
            }
        }
        return $this->render('AppWebBundle:Account:addressform.html.twig', array('form' => $form->createView()));
    }
    
    /**
     * @Route("/addresses/default/{id}", name="address_default", options={"expose"=true})
     */
    public function addressdefaultAction(Request $request, Address $address)
    {
        $em = $this->getDoctrine()->getManager();
        foreach($this->getUser()->getAddresses() as $addr){
            if($addr->getId() != $address->getId()){
                $addr->setDefault(false);
                $em->persist($addr);
            }
        }
        
        $address->setDefault(true);
        $em->persist($address);
        $em->flush();
        
        $this->get('session')->getFlashBag()->add('success', 'Default address updated successfully.');
                
        return $this->redirect($this->generateUrl('address_page'));
    }
    
    /**
     * @Route("/order/place", name="place_order")
     */
    public function placeorderAction(Request $request)
    {
        if($this->getUser()->getCart()->getItems()->count() == 0){
            return $this->redirect($this->generateUrl('cart_page'));
        }
        
        return $this->render('AppWebBundle:Account:placeOrder.html.twig');
    }
    
    /**
     * @Route("/order/submit", name="submit_order")
     */
    public function submitorderAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $address = $em->getRepository('AppFrontBundle:Address')->find($request->get('address'));
                
        $purchase = new Purchase();
        $purchase->setConsumer($this->getUser());
        $purchase->setDeliverTo($address);
        $purchase->setPurchasedOn(new \DateTime('now'));
        $purchase->setStatus(0);
        
        $em->persist($purchase);
        $em->flush();
        
        $cart = $this->getUser()->getCart();
        
        foreach($cart->getItems() as $item){
            //create purchase item entry
            $purchaserItem = new PurchaseItem();
            $purchaserItem->setPurchase($purchase);
            $purchaserItem->setEntry($item->getEntry());
            $purchaserItem->setQuantity($item->getQuantity());
            $purchaserItem->setStatus(0);
            $purchaserItem->setPrice($item->getQuantity()*$item->getPrice());
            
            // create stock purchase history
            $purchase = new StockPurchase();
            $purchase->setUser($this->getUser());
            $purchase->setPrice($item->getPrice());
            $purchase->setQuantity($item->getQuantity());
            $purchase->setReverse(FALSE);
            $purchase->setStockItem($item->getEntry());
            $purchase->setPurchsedOn(new \DateTime('now'));
            
            $em->persist($purchase);
            $em->persist($purchaserItem);
            $em->remove($item);
        }
        
        $em->flush();
        
        return $this->redirect($this->generateUrl('order_thanks'));
    }
    
    /**
     * @Route("/order/thankyou", name="order_thanks")
     */
    public function orderthanksAction(Request $request)
    {
        return $this->render('AppWebBundle:Account:orderThanks.html.twig');
    }
}
