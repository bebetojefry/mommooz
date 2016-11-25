<?php

namespace App\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AppWebBundle:Index:index.html.twig');
    }
}
