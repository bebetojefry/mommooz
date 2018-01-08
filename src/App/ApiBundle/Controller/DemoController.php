<?php

namespace App\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

class DemoController extends FOSRestController
{
    /**
     * @Rest\Get("/demo")
     */
    public function demoAction()
    {
        $data = array("hello" => "world");

        $view = $this->view($data);
        return $this->handleView($view);
    }
}
