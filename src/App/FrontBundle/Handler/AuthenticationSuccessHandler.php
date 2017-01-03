<?php
namespace App\FrontBundle\Handler;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\FrontBundle\Entity\Cart;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AuthenticationSuccessHandler extends DefaultAuthenticationSuccessHandler {

    private $container;
    public function __construct(HttpUtils $httpUtils, array $options, $container) {
        parent::__construct($httpUtils, $options);
        $this->container = $container;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token) {
        $em = $this->container->get('doctrine')->getManager();
        
        if($request->get('region') && $request->get('location')){
            $this->container->get('session')->set('region', $request->get('region'));
            $this->container->get('session')->set('location', $request->get('location'));
        }
        
        if($request->query->get('cart')){
            $old_cart = $em->getRepository('AppFrontBundle:Cart')->find($request->query->get('cart'));
            $cart = $token->getUser()->getCart();
            if(!$cart) {
                $cart = new Cart();
                $cart->setSessionId(session_id());
                $cart->setUser($token->getUser());
            }
            
            $em->persist($cart);
            $em->flush();
            
            foreach($old_cart->getItems() as $item){
                $item->setCart($cart);
                $em->persist($item);
            }
            
            $em->flush();
            
            $em->remove($old_cart);
            $em->flush();
            
            return new RedirectResponse($this->container->get('router')->generate('cart_page'));
        }
        
        $failureRepo = $em->getRepository('AppFrontBundle:Loginfailure');
        $failureRepo->removeFailures($token->getUser(), $request->server->get('REMOTE_ADDR'));
        if($request->isXmlHttpRequest()) {
            $response = new JsonResponse(array('success' => true, 'username' => $token->getUsername()));
        } else {
            if($token instanceof UsernamePasswordToken){
                $referer = $request->getSession()->get('_security.'.$token->getProviderKey().'.target_path');
                if($referer){
                    $response = new RedirectResponse($referer);
                } else {
                    $response = parent::onAuthenticationSuccess($request, $token);
                }
            } else {
                $response = parent::onAuthenticationSuccess($request, $token);
            }
        }
        
        return $response;
    }
}