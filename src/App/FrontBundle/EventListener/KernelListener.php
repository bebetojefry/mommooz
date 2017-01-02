<?php

namespace App\FrontBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use App\FrontBundle\Helper\FormHelper;

class KernelListener {
    
    public function onKernelException(GetResponseForExceptionEvent $event)
    {        
        $exception = $event->getException();
        if($exception instanceof ForeignKeyConstraintViolationException){
            $content = json_encode(array('code' => FormHelper::FOREIGN_INTEGRITY_EXCEPTION, 'data' => ''));
            $response = new Response();
            $response->setContent($content);
            $response->setStatusCode(Response::HTTP_LOCKED);
            $event->setResponse($response);
        }
    }
}
