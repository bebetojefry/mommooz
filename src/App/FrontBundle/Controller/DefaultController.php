<?php

namespace App\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\FrontBundle\Entity\User;
Use App\FrontBundle\Entity\Product;
use App\FrontBundle\Form\ProductType;
use App\FrontBundle\Document\User as MongoUser;

class DefaultController extends Controller {

    public function setlangAction($route, Request $request) {
        return $this->redirect($this->get('nzo_url_encryptor')->decrypt($route));
    }

}