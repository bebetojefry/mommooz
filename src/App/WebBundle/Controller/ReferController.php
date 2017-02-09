<?php

namespace App\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Entity\Reference;
use App\FrontBundle\Form\ReferenceType;

class ReferController extends Controller
{
    /**
     * @Route("/", name="refer_a_friend")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $obj = new \stdClass();
        $obj->refers = array(new Reference());
        $form = $this->createFormBuilder($obj)
                ->add('refers', 'collection', array(
                    'type'         => new ReferenceType(),
                    'allow_add'    => true,
                    'by_reference' => false,
                    'allow_delete' => true,
                ))->getForm();
        
        $msg = null;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $obj = $form->getData();
                foreach($obj->refers as $reference){
                    $reference->setReferedBy($this->getUser());
                    $em->persist($reference);
                    
                    //send email to friend
                    $content = $this->renderView('AppWebBundle:Refer:email.html.twig',
                        array('consumer' => $this->getUser(), 'friend' => $reference)
                    );

                    $message = \Swift_Message::newInstance()
                    ->setSubject('Mommooz Reference')
                    ->setFrom($this->getParameter('email_from'))
                    ->addTo($reference->getEmail(), $reference->getName())
                    ->setBody($content, 'text/html');

                    $this->get('mailer')->send($message);
                }
                $em->flush();
                
                $msg = 'Reference to friend(s) has been successfully sent.';
            }
        }
        return $this->render('AppWebBundle:Refer:index.html.twig', array('form' => $form->createView(), 'msg' => $msg));
    }
}
