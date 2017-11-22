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
use App\FrontBundle\Entity\Reward;
use App\FrontBundle\Entity\RewardUse;
use Ob\HighchartsBundle\Highcharts\Highchart;
use App\FrontBundle\Entity\Cart;

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
        $consumer->setGender(1);
        $address = new Address();
        $address->setDefault(true);
        $address->setState('Kerala');
        $consumer->addAddress($address);
        $form = $this->createForm(new RegisterType($this->get('router')), $consumer);
        $msg = '';
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $consumer = $form->getData();
                $consumers = $em->getRepository('AppFrontBundle:Consumer')->findByEmail($consumer->getEmail());
                if(count($consumers) > 0){
                    $msg = 'Email ID already in use';
                } else {
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

                    try {
                        $this->get('mailer')->send($message);
                    } catch(\Exception $e) {

                    }

                    return $this->redirect($this->generateUrl('register_thank_you'));
                }
            }
        }
        
        return $this->render('AppWebBundle:Account:register.html.twig', array('form' => $form->createView(), 'msg' => $msg));
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
                    
                    // check for reference
                    $refernce = $em->getRepository('AppFrontBundle:Reference')->findOneByEmail($consumer->getEmail(), array('id' => 'ASC'));
                    if($refernce){
                        $ref_reward = $em->getRepository('AppFrontBundle:Config')->findOneByName('reference_reward');
                        if($ref_reward){
                            $reward = new Reward();
                            $reward->setConsumer($refernce->getReferedBy());
                            $reward->setCreditedOn(new \DateTime('now'));
                            $reward->setPoint($ref_reward->getValue());
                            $reward->setSource('reference');
                            
                            $em->persist($reward);
                            $em->flush();
                        }
                    }
                    
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
     * @Route("/forgot", name="forgot_consumer_password")
     */
    public function forgotAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if($request->isMethod('POST')) {
            $consumer = $em->getRepository('AppFrontBundle:Consumer')->findOneByEmail($_POST['email']);
            if($consumer){
                //send verification email to customer
                $content = $this->renderView('AppWebBundle:Account:forgotTemplate.html.twig',
                    array('consumer' => $consumer)
                );

                $message = \Swift_Message::newInstance()
                ->setSubject('Mommooz - Password Reset')
                ->setFrom($this->getParameter('email_from'))
                ->addTo($consumer->getEmail(), $consumer->getFullName())
                ->setBody($content, 'text/html');
                
                try {
                    $this->get('mailer')->send($message);
                } catch(\Exception $e) {
                    
                }

                return $this->redirect($this->generateUrl('forgot_submit'));
            } else {
                $request->getSession()
                    ->getFlashBag()
                    ->add('forgot_error', 'Given email ID doesn\'t exist');
            }
        }
        
        return $this->render('AppWebBundle:Account:forgot.html.twig');
    }
    
    /**
     * @Route("/request_email", name="request_consumer_email")
     */
    public function requestEmailAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if($request->isMethod('POST')) {
            $consumer = $em->getRepository('AppFrontBundle:Consumer')->findOneByEmail($_POST['email']);
            if($consumer == null){
                $user = $this->getUser();
                $user->setEmail($_POST['email']);
                $user->setUsername($_POST['email']);
                $user->setEnabled(true);
                $em->persist();
                $em->flush();
                
                return $this->redirect($this->generateUrl('home'));
            } else {
                $request->getSession()
                    ->getFlashBag()
                    ->add('email_request_error', 'Given email ID already in use.');
            }
        }
        
        return $this->render('AppWebBundle:Account:email_request.html.twig');
    }
    
    /**
     * @Route("/forgot/submit", name="forgot_submit")
     */
    public function forgotsubmitAction()
    {
        if($this->getUser()){            
            return $this->redirect($this->generateUrl('home'));
        }
        return $this->render('AppWebBundle:Account:forgotsubmit.html.twig');
    }
    
    /**
     * @Route("/reset/{id}", name="reset_consumer_password")
     */
    public function resetAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $consumer_id = $this->get('nzo_url_encryptor')->decrypt($id);
        if(is_numeric($consumer_id)){
            $consumer = $em->getRepository('AppFrontBundle:Consumer')->find($consumer_id);
            if($consumer){
                if($request->isMethod('POST')) {
                    if($request->get('password') != $request->get('password_confirm')){
                        $request->getSession()
                        ->getFlashBag()
                        ->add('reset_error', 'Given password doesn\'t match');
                    } else {
                        $encoder = $this->get('security.encoder_factory')->getEncoder($consumer);
                        if($consumer->getSalt() == null){
                            $consumer->regenerateSalt();
                        }
                        $password = $encoder->encodePassword($request->get('password'), $consumer->getSalt());
                        $consumer->setPassword($password);
                        $em->persist($consumer);
                        $em->flush();
                        
                        return $this->redirect($this->generateUrl('reset_submit', array('id' => $id)));
                    }
                }
                
                return $this->render('AppWebBundle:Account:reset.html.twig', array('consumer' => $consumer));
            }
        }
        
        return new Response('Invalid password reset url...', 404);
    }
    
    /**
     * @Route("/reset/{id}/submit", name="reset_submit")
     */
    public function resetsubmitAction()
    {
        if($this->getUser()){            
            return $this->redirect($this->generateUrl('home'));
        }
        
        return $this->render('AppWebBundle:Account:resetsubmit.html.twig');
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
        $em = $this->getDoctrine()->getManager();
        $pending = $em->getRepository('AppFrontBundle:Purchase')->findBy(array('consumer' => $this->getUser(), 'status' => array(0, 1, 2, 3) ));
        $delivered = $em->getRepository('AppFrontBundle:Purchase')->findBy(array('consumer' => $this->getUser(), 'status' => 4 ));
        $cancelled = $em->getRepository('AppFrontBundle:Purchase')->findBy(array('consumer' => $this->getUser(), 'status' => 5 ));
        
        $rewards = $em->getRepository('AppFrontBundle:Reward')->findBy(array('consumer' => $this->getUser()));
        $used = $em->getRepository('AppFrontBundle:RewardUse')->findBy(array('consumer' => $this->getUser()));
        
        $credit = 0;
        foreach($rewards as $reward){
            $credit += $reward->getPoint();
        }
        
        $debit = 0;
        foreach($used as $use){
            $debit += $use->getPoints();
        }
        
        return $this->render('AppWebBundle:Account:index.html.twig',
            array(
                'pending' => $pending,
                'delivered' => $delivered,
                'cancelled' => $cancelled,
                'credit' => $credit,
                'debit' => $debit
            )
        );
    }
    
    /**
     * @Route("/cart", name="cart_page")
     */
    public function cartAction()
    {
        $em = $this->getDoctrine()->getManager();
        $reward_money_config = $em->getRepository('AppFrontBundle:Config')->findOneByName('reward_money');
        $reward_money = 0;
        $total_rewards = 0;
        if($reward_money_config && $this->getUser()){
            if(!$this->getUser()->getCart()){
                $cart = new Cart();
                $cart->setUser($this->getUser());
                $cart->setSessionId(session_id());
                $em->persist($cart);
                $em->flush();
                $this->getUser()->setCart($cart);
            }
            
            $cart_price = $this->getUser()->getCart()->getPrice();
            $max_points_needed = round($cart_price*$reward_money_config->getValue(), 2);
            $rewards = $em->getRepository('AppFrontBundle:Reward')->findByConsumer($this->getUser());
            foreach($rewards as $reward){
                $total_rewards += $reward->getPoint();
                if($total_rewards > $max_points_needed){
                    $total_rewards = $max_points_needed;
                    break;
                }
            }
            
            if($reward_money_config->getValue() > 0){
                $reward_money = round($total_rewards/$reward_money_config->getValue(), 2);
            }
        }
        
        return $this->render('AppWebBundle:Account:cart.html.twig', 
            array('reward_money' => $reward_money, 'total_rewards' => $total_rewards)
        );
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
        $message = '';
        
        $obj = new \stdClass();
        $obj->password = '';
        $form_pwd = $this->createFormBuilder($obj)
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
        
        if($request->isMethod('POST')){
            if(isset($_POST['btn_profile'])){
                $form->handleRequest($request);
                if($form->isValid()){
                    $consumer = $form->getData();
                    $em->persist($consumer);
                    $em->flush();

                    $this->get('session')->getFlashBag()->add('success', 'Profile updated successfully.');

                    return $this->redirect($this->generateUrl('profile_page'));
                }
            } else {
                $form_pwd->handleRequest($request);
                if($form_pwd->isValid()){
                    $obj = $form_pwd->getData();
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
        }
        
        return $this->render('AppWebBundle:Account:profile.html.twig', 
            array('form' => $form->createView(), 'form_pwd' => $form_pwd->createView(), 'message' => $message)
        );
    }
    
    /**
     * @Route("/orders", name="orders_page")
     */
    public function ordersAction()
    {
        $em = $this->getDoctrine()->getManager();
        $pending = $em->getRepository('AppFrontBundle:Purchase')->findBy(array('consumer' => $this->getUser(), 'status' => array(0, 1, 2, 3) ));
        $delivered = $em->getRepository('AppFrontBundle:Purchase')->findBy(array('consumer' => $this->getUser(), 'status' => 4 ));
        $cancelled = $em->getRepository('AppFrontBundle:Purchase')->findBy(array('consumer' => $this->getUser(), 'status' => 5 ));
        return $this->render('AppWebBundle:Account:orders.html.twig',
            array(
                'pending' => $pending,
                'delivered' => $delivered,
                'cancelled' => $cancelled,
                'status' => array(0 => 'Pending', 1 => 'Confirmed', 2 => 'Processing', 3=> "Out for delivered", 4 => 'Delivered', 5 => 'Cancelled')
            )
        );
    }
    
    /**
     * @Route("/rewards", name="rewards_page")
     */
    public function rewardsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $rewards = $em->getRepository('AppFrontBundle:Reward')->findBy(array('consumer' => $this->getUser()));
        $used = $em->getRepository('AppFrontBundle:RewardUse')->findBy(array('consumer' => $this->getUser()));
        return $this->render('AppWebBundle:Account:rewards.html.twig',
            array(
                'rewards' => $rewards,
                'used' => $used,
            )
        );
    }
    
    /**
     * @Route("/report", name="report_page")
     */
    public function reportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('pi')
            ->from('AppFrontBundle:PurchaseItem', 'pi')
            ->join('pi.purchase', 'p')
            ->where('p.status = :status and p.consumer = :consumer')
            ->setParameter('status', 4)
            ->setParameter('consumer', $this->getUser());

        $items = $qb->getQuery()->getResult();
        
        $categories = array();
        foreach($items as $item){
            $cat = $item->getEntry()->getItem()->getProduct()->getCategory()->getRoot();
            if(isset($categories[$cat->getId()])){
                $categories[$cat->getId()]['items'][] = $item;
                $categories[$cat->getId()]['total'] += $item->getPrice();
            } else {
                $categories[$cat->getId()]['meta'] = $cat;
                $categories[$cat->getId()]['items'] = array($item);
                $categories[$cat->getId()]['total'] = $item->getPrice();
            }
        }
        
        $ob = new Highchart();
        $ob->chart->renderTo('pie');
        $ob->chart->type('pie');
        $ob->title->text('Your Expenditure');
        $ob->plotOptions->series(
            array(
                'dataLabels' => array(
                    'enabled' => true,
                    'format' => '{point.name}: Rs.{point.y:.1f}'
                )
            )
        );

        $ob->tooltip->headerFormat('<span style="font-size:11px">{series.name}</span><br>');
        $ob->tooltip->pointFormat('<span style="color:{point.color}">{point.name}</span>: <b>Rs.{point.y:.2f}</b><br/>');

        $data = array();
        foreach($categories as $cat){
            $data[] = array($cat['meta']->getCategoryName(), $cat['total']);
        }
        
        $ob->series(
            array(
                array(
                    'name' => 'Expense',
                    'colorByPoint' => true,
                    'data' => $data
                )
            )
        );

        return $this->render('AppWebBundle:Account:report.html.twig',
            array(
                'categories' => $categories,
                'chart' => $ob
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
        
        // delete all stock purchases related to this purchase
        $qb = $em->createQueryBuilder();
        $qb->delete('AppFrontBundle:StockPurchase', 'sp');
        $qb->where('sp.purchase = :purchase');
        $qb->setParameter('purchase', $order);
        $qb->getQuery()->execute();
        
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
                
                if(isset($_POST['from_cart'])){
                    return $this->redirect($this->generateUrl('place_order'));
                }
                
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
        
        $em = $this->getDoctrine()->getManager();
        $reward_money_config = $em->getRepository('AppFrontBundle:Config')->findOneByName('reward_money');
        $reward_money = 0;
        $total_rewards = 0;
        if($reward_money_config && $this->getUser()){
            $cart_price = $this->getUser()->getCart()->getPrice();
            $max_points_needed = round($cart_price*$reward_money_config->getValue(), 2);
            $rewards = $em->getRepository('AppFrontBundle:Reward')->findByConsumer($this->getUser());
            foreach($rewards as $reward){
                $total_rewards += $reward->getPoint();
                if($total_rewards > $max_points_needed){
                    $total_rewards = $max_points_needed;
                    break;
                }
            }
            
            if($reward_money_config->getValue() > 0){
                $reward_money = round($total_rewards/$reward_money_config->getValue(), 2);
            }
        }

        $delivery_charge = 0;
        $charges = $em->getRepository("AppFrontBundle:DeliveryCharge")->createQueryBuilder('c')
            ->where('c.priceFrom >= :p and c.priceTo <= :p')
            ->setParameter('p', $this->getUser()->getCart()->getPrice())
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        if(count($charges) > 0){
            $delivery_charge = $charges[0]->getCharge();
        }
        
        return $this->render('AppWebBundle:Account:placeOrder.html.twig', 
            array('reward_money' => $reward_money, 'total_rewards' => $total_rewards, 'delivery_charge' => $delivery_charge)
        );
    }
    
    /**
     * @Route("/order/pay", name="pay_order")
     */
    public function payorderAction(Request $request)
    {
        if(isset($_POST['buy_now'])){
            $this->get('session')->set('buy_now', $_POST['buy_now']);
        }
        
        if(isset($_POST['cod'])){
            $this->get('session')->set('order_method', 'COD');
            $this->get('session')->set('address', $request->get('address'));
            return $this->forward('AppWebBundle:Account:ordersuccess');
        }
        
        $em = $this->getDoctrine()->getManager();
        $address = $em->getRepository('AppFrontBundle:Address')->find($request->get('address'));
        
        if($address){
            $merchant_data = '';
            $working_key = '3B8AE5C662C3123B31BACF1454781F6B';
            $access_code = 'AVSU70ED20AW44USWA';
            
            $total_amt = 0;
            $cart = $this->getUser()->getCart();
            foreach($cart->getItems() as $item){
                $total_amt += $item->getQuantity()*$item->getPrice();
            }
            
            if(isset($_POST['use_reward'])){
                $total_amt -= $_POST['reward_money'];
            }
            
            $data = array(
                'tid' => time(),
                'merchant_id' => '130331',
                'order_id' => time(),
                'amount' => $total_amt,
                'currency' => 'INR',
                'redirect_url' => $this->generateUrl('submit_order_payed', array(), true),
                'cancel_url' => $this->generateUrl('submit_order_payed', array(), true),
                'language' => 'EN',
                'billing_name' => $this->getUser()->getFullName(),
                'billing_address' => $address->getHouse(),','.$address->getStreet(),','.$address->getLandmark(),
                'billing_city' => $address->getCity(),
                'billing_state' => 'KL',
                'billing_zip' => $address->getPin(),
                'billing_country' => 'India',
                'billing_tel' => $this->getUser()->getPhone(),
                'billing_email' => 'info@momooz.com',
                'delivery_name' => $this->getUser()->getFullName(),
                'delivery_address' => $address->getHouse(),','.$address->getStreet(),','.$address->getLandmark(),
                'delivery_city' => $address->getCity(),
                'delivery_state' => 'KL',
                'delivery_zip' => $address->getPin(),
                'delivery_country' => 'India',
                'delivery_tel' => $this->getUser()->getPhone(),
            );
            
            $this->get('session')->set('address', $request->get('address'));
            
            if(isset($_POST['use_reward'])){
                $this->get('session')->set('use_reward', $_POST['use_reward']);
                $this->get('session')->set('reward_points_used', $_POST['reward_points_used']);
                $this->get('session')->set('reward_money', $_POST['reward_money']);
            }
            
            foreach ($data as $key => $value){
                    $merchant_data.=$key.'='.urlencode($value).'&';
            }

            $encrypted_data = $this->get('app.ccavenue.crypt')->encrypt($merchant_data, $working_key);
            
            return $this->render('AppWebBundle:Account:pay.html.twig', array(
                'encrypted_data' => $encrypted_data,
                'access_code' => $access_code
            ));
            
        } else {
            return $this->redirect($this->generateUrl('cart_page'));
        }
        
    }
    
    /**
     * @Route("/order/submit", name="submit_order_payed")
     */
    public function submitorderAction(Request $request)
    {
        $workingKey = '3B8AE5C662C3123B31BACF1454781F6B';
        $encResponse = $_POST["encResp"];
        $rcvdString = $this->get('app.ccavenue.crypt')->decrypt($encResponse, $workingKey);
        $order_status = "";
        $decryptValues = explode('&', $rcvdString);
        $dataSize = sizeof($decryptValues);
        echo "<center>";

        for($i = 0; $i < $dataSize; $i++)
        {
                $information=explode('=',$decryptValues[$i]);
                if($i==3)	$order_status=$information[1];
        }

        if($order_status==="Success")
        {
                $this->get('session')->set('order_method', 'PAID');
                return $this->forward('AppWebBundle:Account:ordersuccess');

        }
        else if($order_status==="Aborted")
        {
                return $this->render('AppWebBundle:Account:pay_status.html.twig', array(
                    'status' => '<b>Aborted: </b>Thank you for shopping with us.We will keep you posted regarding the status of your order through e-mail.'
                ));

        }
        else if($order_status==="Failure")
        {
            return $this->render('AppWebBundle:Account:pay_status.html.twig', array(
                    'status' => '<b>Failure: </b>Thank you for shopping with us.However,the transaction has been declined.'
                ));
        }
        else
        {
                return $this->render('AppWebBundle:Account:pay_status.html.twig', array(
                    'status' => '<b>Error: </b>Security Error. Illegal access detected.'
                ));

        }
    }
    
    /**
     * @Route("/order/success", name="order_success")
     */
    public function ordersuccessAction(Request $request)
    {
        if($this->getUser()){
            $em = $this->getDoctrine()->getManager();
            $address = $em->getRepository('AppFrontBundle:Address')->find($this->get('session')->get('address'));

            $delivery_charge = 0;
            $charges = $em->getRepository("AppFrontBundle:DeliveryCharge")->createQueryBuilder('c')
                ->where('c.priceFrom >= :p and c.priceTo <= :p')
                ->setParameter('p', $this->getUser()->getCart()->getPrice())
                ->setFirstResult(0)
                ->setMaxResults(1)
                ->getQuery()
                ->getResult();

            if(count($charges) > 0){
                $delivery_charge = $charges[0]->getCharge();
            }

            $purchase = new Purchase();
            $purchase->setConsumer($this->getUser());
            $purchase->setDeliverTo($address);
            $purchase->setPurchasedOn(new \DateTime('now'));
            $purchase->setMethod($this->get('session')->get('order_method'));
            $purchase->setDeliveryCharge($delivery_charge);
            $purchase->setStatus(0);

            $em->persist($purchase);
            $em->flush();
            
            if($entry_id = $this->get('session')->get('buy_now')){
                $this->get('session')->remove('buy_now');
                $entry = $em->getRepository('AppFrontBundle:StockEntry')->find($entry_id);
                $cart = new Cart();
                $cart_item = new CartItem();
                $cart_item->setEntry($entry);
                $cart_item->setQuantity(1);
                $cart_item->setPrice($entry->getActualPrice());
                $cart_item->setStatus(false);
                $cart->addItem($cart_item);
            } else {
                $cart = $this->getUser()->getCart();
            }

            $total_amt = 0;
            foreach($cart->getItems() as $item){
                //create purchase item entry
                $purchaserItem = new PurchaseItem();
                $purchaserItem->setPurchase($purchase);
                $purchaserItem->setEntry($item->getEntry());
                $purchaserItem->setQuantity($item->getQuantity());
                $purchaserItem->setStatus(0);
                $purchaserItem->setUnitPrice($item->getPrice());
                $purchaserItem->setPrice($item->getQuantity()*$item->getPrice());
                $em->persist($purchaserItem);

                $total_amt += $item->getQuantity()*$item->getPrice();

                // create stock purchase history
                $stock_purchase = new StockPurchase();
                $stock_purchase->setUser($this->getUser());
                $stock_purchase->setPurchase($purchase);
                $stock_purchase->setPrice($item->getPrice());
                $stock_purchase->setQuantity($item->getQuantity());
                $stock_purchase->setReverse(FALSE);
                $stock_purchase->setStockItem($item->getEntry());
                $stock_purchase->setPurchsedOn(new \DateTime('now'));
                $em->persist($stock_purchase);
                
                // remove from user cart if its real
                if($item->getId() !== null){
                    $em->remove($item);
                }
            }

            if($this->get('session')->get('use_reward')){
                $reward = new Reward();
                $reward->setConsumer($this->getUser());
                $reward->setPoint($this->get('session')->get('reward_points_used')*-1);
                $reward->setSource(Reward::PURCHASE);
                $reward->setCreditedOn(new \DateTime('now'));

                $em->persist($reward);

                $reward_use = new RewardUse();
                $reward_use->setConsumer($this->getUser());
                $reward_use->setPurchase($purchase);
                $reward_use->setPoints($this->get('session')->get('reward_points_used'));
                $reward_use->setMoney($this->get('session')->get('reward_money'));

                $em->persist($reward_use);
            }

            // configure reward
            $reward_config = $em->getRepository('AppFrontBundle:Config')->findOneByName('purchase_reward');
            if($reward_config){
                $val = $reward_config->getValue();
                if($val > 0){
                    $points = round($total_amt/$val, 2);

                    $reward = new Reward();
                    $reward->setConsumer($this->getUser());
                    $reward->setPoint($points);
                    $reward->setSource(Reward::PURCHASE);
                    $reward->setCreditedOn(new \DateTime('now'));

                    $em->persist($reward);
                }
            }

            $em->flush();
            
            //send order email to customer
            $content = $this->renderView('AppWebBundle:Account:orderTemplate.html.twig',
                array('order' => $purchase)
            );

            $message = \Swift_Message::newInstance()
            ->setSubject('Mommooz Order')
            ->setFrom($this->getParameter('email_from'))
            ->addTo($this->getUser()->getEmail(), $this->getUser()->getFullName())
            ->setBody($content, 'text/html');
            
            $admin_content = $this->renderView('AppWebBundle:Account:orderAdminTemplate.html.twig',
                array('order' => $purchase)
            );

            $admin_message = \Swift_Message::newInstance()
            ->setSubject('Mommooz New Order')
            ->setFrom($this->getParameter('email_from'))
            ->addTo('info@momooz.com', 'Momooz Admin')
            ->setBody($admin_content, 'text/html');

            try {
                $this->get('mailer')->send($message);
                $this->get('mailer')->send($admin_message);
            } catch(\Exception $e) {

            }

            return $this->redirect($this->generateUrl('order_thanks'));
        } else {
            return $this->render('AppWebBundle:Account:pay_status.html.twig', array(
                'status' => '<b>Error: </b>Your purchase session timed out.'
            ));
        }
    }
    
    /**
     * @Route("/order/thankyou", name="order_thanks")
     */
    public function orderthanksAction(Request $request)
    {
        return $this->render('AppWebBundle:Account:orderThanks.html.twig');
    }
    
    /**
     * @Route("/email/validate", name="consumer_email_validate")
     */
    public function emailValidateAction()
    {
        $email = $_GET['app_frontbundle_user']['email'];
        $vendors = $this->getDoctrine()->getManager()->getRepository("AppFrontBundle:Consumer")->createQueryBuilder('c')
            ->where('c.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getResult();
        
        if(count($vendors) == 0){
            return new Response('OK', 200);
        }
        
        return new Response('Invalid', 406);
    }
}
