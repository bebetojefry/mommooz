<?php

namespace App\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Entity\Vendor;

class StoreController extends Controller
{
    /**
     * @Route("/{id}", name="stores", options={"expose"=true}, defaults={"id" = 0})
     */
    public function indexAction(Vendor $store = null)
    {
        echo get_class($store); exit;
    }
}
